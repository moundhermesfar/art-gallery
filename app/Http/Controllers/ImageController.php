<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
  public function download(Request $request)
  {
    $imageId = $request->get('image_id');
    $title = $request->get('title', 'artwork');
    $imagePath = $request->get('image_path');

    if (!$imageId && !$imagePath) {
      abort(400, 'Image ID or Image Path is required');
    }

    try {
      if ($imageId) {
        // Handle API images: Construct the external image URL
        $imageUrl = "https://www.artic.edu/iiif/2/{$imageId}/full/843,/0/default.jpg";

        // Fetch the image from the external API
        $response = Http::timeout(30)->get($imageUrl);

        if (!$response->successful()) {
          abort(404, 'Image not found');
        }

        // Get the content type from the response
        $contentType = $response->header('content-type') ?? 'image/jpeg';

        // Determine file extension from content type
        $extension = match ($contentType) {
          'image/png' => '.png',
          'image/gif' => '.gif',
          'image/webp' => '.webp',
          default => '.jpg'
        };

        // Clean the title for use as filename
        $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $title) . $extension;

        return response($response->body())
          ->header('Content-Type', $contentType)
          ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
          ->header('Content-Length', strlen($response->body()));
      } else {
        // Handle local images: Extract the storage path from the full URL
        // $imagePath is something like: http://localhost:8000/storage/user-images/filename.jpg
        // We need to convert it to: user-images/filename.jpg

        // Parse the URL to extract the path after '/storage/'
        $parsedUrl = parse_url($imagePath);
        $path = $parsedUrl['path'] ?? '';

        // Check if the path contains '/storage/'
        if (!str_contains($path, '/storage/')) {
          abort(400, 'Invalid local image path');
        }

        // Extract the relative path after '/storage/'
        $storageIndex = strpos($path, '/storage/') + strlen('/storage/');
        $relativePath = substr($path, $storageIndex);

        // Get the full file path in storage
        $filePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($filePath)) {
          Log::error('File not found: ' . $filePath);
          abort(404, 'Local image file not found');
        }

        // Get file info
        $mimeType = mime_content_type($filePath);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        // Clean the title for use as filename
        $filename = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $title) . '.' . $fileExtension;

        return response()->download($filePath, $filename, [
          'Content-Type' => $mimeType,
        ]);
      }
    } catch (\Exception $e) {
      Log::error('Image download failed: ' . $e->getMessage());
      abort(500, 'Failed to download image: ' . $e->getMessage());
    }
  }
}
