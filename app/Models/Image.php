<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class Image extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'title',
    'description',
    'path',
  ];

  protected $appends = ['path_url'];

  public function getPathUrlAttribute(): ?string
  {
    return $this->path
      ? asset('storage/' . $this->path)
      : null;
  }

  public function getIsFavoritedAttribute(): bool
  {
    if (!Auth::check()) {
      return false;
    }

    return Favorite::where('user_id', Auth::id())
      ->where('source', 'local')
      ->where('image_id', $this->id)
      ->exists();
  }
}
