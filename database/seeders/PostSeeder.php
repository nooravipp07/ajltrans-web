<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title_id' => 'AJL Trans Ekspansi ke Jakarta',
                'title_en' => 'AJL Trans Expands to Jakarta',
                'slug' => Str::slug('AJL Trans Ekspansi ke Jakarta'),
                'content_id' => 'Kami dengan bangga mengumumkan pembukaan cabang baru di Jakarta untuk melayani kebutuhan transportasi premium Anda.',
                'content_en' => 'We are proud to announce the opening of our new branch in Jakarta to serve your premium transportation needs.',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title_id' => 'Tips Perjalanan Nyaman dengan Alphard',
                'title_en' => 'Tips for a Comfortable Trip with Alphard',
                'slug' => Str::slug('Tips Perjalanan Nyaman dengan Alphard'),
                'content_id' => 'Berikut adalah tips untuk memaksimalkan kenyamanan Anda saat menyewa unit Toyota Alphard kami.',
                'content_en' => 'Here are some tips to maximize your comfort when renting our Toyota Alphard units.',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
