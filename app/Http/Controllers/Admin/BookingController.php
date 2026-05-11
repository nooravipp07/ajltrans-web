<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use Inertia\Inertia;
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'vehicle'])
            ->latest()
            ->get();
            
        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings
        ]);
    }

    public function export()
    {
        return Excel::download(new BookingsExport, 'data_booking_' . date('Y-m-d') . '.xlsx');
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'vehicle', 'mediaDocs']);
        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu_konfirmasi,dikonfirmasi,sedang_berjalan,selesai,dibatalkan',
            'catatan_admin' => 'nullable|string',
        ]);

        $booking->update($validated);

        // Send WhatsApp Notification
        $this->sendStatusNotification($booking);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    private function sendStatusNotification(Booking $booking)
    {
        $statusLabel = str_replace('_', ' ', ucfirst($booking->status));
        $trackingUrl = route('booking.track', $booking->booking_code);
        $testimonialUrl = route('booking.testimonial', $booking->booking_code);

        $message = "Halo *{$booking->customer->nama}*,\n\nStatus pesanan Anda *#{$booking->booking_code}* telah diperbarui menjadi: *{$statusLabel}*.\n";
        
        if ($booking->catatan_admin) {
            $message .= "\nCatatan Admin: {$booking->catatan_admin}\n";
        }

        $message .= "\nLacak status booking: {$trackingUrl}\n";

        if ($booking->status === 'selesai') {
            $message .= "\nTerima kasih! Jika berkenan, silakan isi testimoni di: {$testimonialUrl}\n";
        }

        if ($booking->status === 'dibatalkan') {
            $message .= "\nPesanan Anda dibatalkan. Uang DP tidak dapat dikembalikan.\n";
        }
        
        $message .= "\nTerima kasih telah menggunakan AJL Trans.";
        
        $phone = preg_replace('/[^0-9]/', '', $booking->customer->no_wa);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $waUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
        
        // Since we can't literally "send" without API, we redirect or provide the link.
        // But the user asked for "admin can directly send", usually means a button or auto-open.
        // In web context, we'll store it in session to be opened by the admin.
        session()->flash('wa_notification_url', $waUrl);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);
        return back();
    }

    public function verify($id)
    {
        if (auth('admin')->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang dapat melakukan verifikasi.');
        }

        $booking = Booking::findOrFail($id);
        $booking->update([
            'verified_at' => now(),
            'status' => 'dikonfirmasi' // Auto confirm when verified
        ]);

        $this->sendStatusNotification($booking);

        return back()->with('success', 'Booking berhasil diverifikasi.');
    }
}
