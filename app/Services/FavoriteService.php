<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
  public static function toggle($record, string $source): bool
  {
    $userId = Auth::id();

    if ($source === 'local') {
      $fav = Favorite::where('user_id', $userId)
        ->where('source', 'local')
        ->where('image_id', $record->id)
        ->first();

      if ($fav) {
        $fav->delete();
        return false;
      }

      Favorite::create([
        'user_id' => $userId,
        'source' => 'local',
        'image_id' => $record->id,
      ]);
      return true;
    }

    if ($source === 'api') {
      $fav = Favorite::where('user_id', $userId)
        ->where('source', 'api')
        ->where('api_image_id', $record['image_id'])
        ->first();

      if ($fav) {
        $fav->delete();
        return false;
      }

      Favorite::create([
        'user_id' => $userId,
        'source' => 'api',
        'api_image_id' => $record['image_id'],
        'api_title' => $record['title'],
        'api_description' => $record['description'],
      ]);
      return true;
    }

    return false;
  }
}
