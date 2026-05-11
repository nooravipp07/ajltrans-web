<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCms extends Model
{
    protected $table = 'content_cms';

    protected $fillable = [
        'section',
        'key',
        'value_id',
        'value_en',
        'type',
    ];
}
