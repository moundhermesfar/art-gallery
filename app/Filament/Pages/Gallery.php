<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\View\Components\ButtonComponent;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Http;
use BackedEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Gallery extends Page implements HasTable
{

  use InteractsWithTable;
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

  public function table(Table $table): Table
  {
    return $table
      ->records(
        fn(): array => Http::get($this->URL, ['page' => 1, 'limit' => 12, 'fields' => 'id,title,image_id'])
          ->collect()
          ->get('data', [])
      )
      ->columns([
        Grid::make()
          ->columns(1)
          ->schema([
            TextColumn::make('title')
              ->label('Title')
              ->getStateUsing(fn($record): mixed => \Illuminate\Support\Str::limit($record['title'] ?? 'Untitled', 30))
              ->extraAttributes([
                'class' => 'text-sm font-medium text-center',
              ])
              ->searchable(),
            View::make('components.image-card'),
          ])
          ->extraAttributes([
            'class' => 'flex flex-col items-center justify-center',
          ]),
      ])
      ->contentGrid([
        'sm' => 1,
        'md' => 2,
        'lg' => 3
      ])
      ->paginationPageOptions([6, 12, 24]);
  }
}
