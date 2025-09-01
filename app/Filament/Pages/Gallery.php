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

  public function mount(): void
  {
    $res = Http::get(
      'https://api.artic.edu/api/v1/artworks',
      ['page' => 1, 'limit' => 10, 'fields' => 'id,title,image_id']
    );
    if ($res->successful()) {
      $data = $res->json()['data'];

      $this->images = collect($data)->map(function ($item) {
        $item['image_url'] = "https://www.artic.edu/iiif/2/{$item['image_id']}/full/843,/0/default.jpg";
        return $item;
      })->toArray();
    }
  }
}
