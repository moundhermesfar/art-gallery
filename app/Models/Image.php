<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'path',
        'is_favorite',
    ];

    protected $appends = ['path_url'];

    public function getPathUrlAttribute(): ?string
    {
        return $this->path
            ? asset('storage/' . $this->path)
            : null;
    }
}
