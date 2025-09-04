<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
  public function toggle(Request $request)
  {
    $request->validate([
      'source' => 'required|in:api,local',
      'image_id' => 'nullable|exists:images,id',
      'api_image_id' => 'nullable|string',
    ]);

    $user = Auth::user();

    $query = Favorite::where('user_id', $user->id)
      ->where('source', $request->source);

    if ($request->source === 'local') {
      $query->where('image_id', $request->image_id);
    } else {
      $query->where('api_image_id', $request->api_image_id);
    }

    $favorite = $query->first();

    if ($favorite) {
      $favorite->delete();
      return response()->json(['favorited' => false]);
    } else {
      Favorite::create([
        'user_id' => $user->id,
        'source' => $request->source,
        'image_id' => $request->source === 'local' ? $request->image_id : null,
        'api_image_id' => $request->source === 'api' ? $request->api_image_id : null,
      ]);
      return response()->json(['favorited' => true]);
    }
  }
}
