<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use BackedEnum;

use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class Gallery extends Page
{
  protected string $view = 'filament.pages.gallery';
  protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';
  protected static string | BackedEnum | null $activeNavigationIcon = 'heroicon-s-paint-brush';
  protected static ?int $navigationSort = 1;

  public array $images = [];

  private string $URL = 'https://api.artic.edu/api/v1/artworks';

  private function getImageURL(string $imageId): string
  {
    return "https://www.artic.edu/iiif/2/{$imageId}/full/843,/0/default.jpg";
  }

  public function mount(): void
  {
    $res = Http::get(
      $this->URL,
      ['page' => 1, 'limit' => 10, 'fields' => 'id,title,image_id']
    );
    if ($res->successful()) {
      $data = $res->json()['data'];

      $this->images = collect($data)
        ->filter(fn($item) => $item['image_id'])
        ->map(function ($item) {
          $item['image_url'] = $this->getImageURL($item['image_id']);
          return $item;
        })->toArray();
    }
  }
}
