<?php

namespace App\Models;

use Database\Factories\ArchiveFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'title', 'category', 'description', 'file_path', 'original_filename', 'file_type', 'file_size', 'status'])]
class Archive extends Model
{
    /** @use HasFactory<ArchiveFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
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