<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadService
{
  public static function download($record, string $source): StreamedResponse
  {
    $title = $record['title'] ?? $record->title ?? 'artwork';

    if ($source === 'local') {
      $path = $record->path; // stored path in DB
      $filename = $title . '.' . pathinfo($path, PATHINFO_EXTENSION);
      $fullPath = storage_path('app/public/' . $path);

      if (!file_exists($fullPath)) {
        abort(404, 'File not found.');
      }

      return response()->streamDownload(function () use ($path) {
        echo Storage::disk('public')->get($path);
      }, $filename);
    }

    if ($source === 'api') {
      $imageUrl = $record['image_url'];
      $response = Http::get($imageUrl);

      $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';

      return response()->streamDownload(function () use ($response) {
        echo $response->body();
      }, $title . '.' . $extension);
    }

    abort(404, 'Invalid image source.');
  }
}
