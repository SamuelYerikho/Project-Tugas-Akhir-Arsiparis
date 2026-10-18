<?php

namespace App\Models;

use Database\Factories\ArchiveFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'title',
    'category',
    'description',
    'file_path',
    'original_filename',
    'file_type',
    'file_size',
    'status',
    // kolom OCR
    'ocr_status',
    'ocr_text',
    'ocr_error',
    'ocr_pages',
    'ocr_seconds',
    'ocr_at',
])]
class Archive extends Model
{
    /** @use HasFactory<ArchiveFactory> */
    use HasFactory;

    /**
     * Tipe file yang bisa diproses OCR (harus sama dengan ALLOWED di main.py FastAPI).
     */
    public const OCR_EXTENSIONS = ['png', 'jpg', 'jpeg', 'bmp', 'tif', 'tiff', 'pdf'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size'   => 'integer',
            'ocr_pages'   => 'integer',
            'ocr_seconds' => 'float',
            'ocr_at'      => 'datetime',
        ];
    }

    /**
     * Apakah file arsip ini bisa diproses OCR (berdasarkan ekstensi).
     */
    public function isOcrSupported(): bool
    {
        return in_array(strtolower((string) $this->file_type), self::OCR_EXTENSIONS, true);
    }

    /**
     * Get the user that owns the archive.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the access requests submitted for this archive.
     */
    public function accessRequests(): HasMany
    {
        return $this->hasMany(AccessRequest::class);
    }
}