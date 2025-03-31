<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_id',
        'file_path',
        'mime_type',
        'original_name',
    ];
}
