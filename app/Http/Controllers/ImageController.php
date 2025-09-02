<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
  public function download(Request $request)
  {
    $imageId = $request->get('image_id');
    $title = $request->get('title', 'artwork');

    if (!$imageId) {
      abort(400, 'Image ID is required');
    }

    // Construct the image URL (same as in Gallery class)
    $imageUrl = "https://www.artic.edu/iiif/2/{$imageId}/full/843,/0/default.jpg";

    try {
      // Fetch the image from the external API
      $response = Http::timeout(30)->get($imageUrl);

      if (!$response->successful()) {
        abort(404, 'Image not found');
      }

      // Get the content type from the response
      $contentType = $response->header('content-type') ?? 'image/jpeg';

      // Clean the title for use as filename
      $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $title) . '.jpg';

      return response($response->body())
        ->header('Content-Type', $contentType)
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->header('Content-Length', strlen($response->body()));
    } catch (\Exception $e) {
      abort(500, 'Failed to download image');
    }
  }
}
