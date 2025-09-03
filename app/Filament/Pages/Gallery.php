<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Http;
use BackedEnum;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

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
        fn(?string $sortColumn, ?string $sortDirection): Collection => collect(Http::get($this->URL, ['page' => 1, 'limit' => 12, 'fields' => 'id,title,image_id'])->json('data', []))
          ->when(
            filled($sortColumn),
            fn(Collection $data) => $data->sortBy($sortColumn, SORT_REGULAR, $sortDirection === 'desc')
          )
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
              ->sortable(true)
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
