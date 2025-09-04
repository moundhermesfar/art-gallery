<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Favorite extends Model
{
  protected $fillable = [
    'user_id',
    'source',
    'image_id',
    'api_image_id',
    'api_description',
    'api_title'
  ];

  protected $appends = ['display_title', 'is_favorited', 'description', 'image_url', 'path_url'];

  public function getDisplayTitleAttribute()
  {
    if ($this->source === 'local' && $this->image) {
      return $this->image->title;
    }

    if ($this->source === 'api') {
      return $this->api_title ?? 'Title';
    }

    return 'Untitled';
  }

  public function getImageUrlAttribute()
  {
    if ($this->source === 'local' && $this->image) {
      return asset('storage/' . $this->image->path);
    }

    if ($this->source === 'api') {
      return "https://www.artic.edu/iiif/2/{$this->api_image_id}/full/843,/0/default.jpg";
    }

    return null;
  }

  public function getDescriptionAttribute()
  {
    if ($this->source === 'local' && $this->image) {
      return $this->image->description;
    }

    if ($this->source === 'api') {
      return $this->api_description ?? 'No description available.';
    }

    return null;
  }

  public function image()
  {
    return $this->belongsTo(Image::class, 'image_id');
  }

  public function getIsFavoritedAttribute()
  {
    return true; // since it’s in favorites table, it’s already favorited
  }
}
