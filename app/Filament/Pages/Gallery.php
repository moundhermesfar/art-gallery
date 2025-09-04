<?php

namespace App\Filament\Pages;

use App\Services\DownloadService;
use App\Services\FavoriteService;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Http;
use BackedEnum;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\Favorite;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class Gallery extends Page implements HasTable
{

  use InteractsWithTable;

  protected string $view = 'filament.pages.gallery';
  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';
  protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-paint-brush';
  protected static ?int $navigationSort = 1;

  public array $images = [];

  private string $URL = 'https://api.artic.edu/api/v1/artworks';

  public string $imageBaseURL;

  public function getImageURL(string $imageId): string
  {
    return $this->imageBaseURL . "/{$imageId}/full/843,/0/default.jpg";
  }

  private function checkIsFavorite(string $imageId): bool
  {
    if (!Auth::check()) {
      return false;
    }

    return Favorite::where('user_id', Auth::id())
      ->where('source', 'api')
      ->where('api_image_id', $imageId)
      ->exists();
  }

  public function table(Table $table): Table
  {
    return $table
      ->records(
        function (?string $sortColumn, ?string $sortDirection, ?string $search, int $page, int $recordsPerPage): LengthAwarePaginator {
          $response =  Http::get($this->URL . ($search ? '/search' : ''), ['q' => $search, 'page' => $page, 'limit' => $recordsPerPage, 'fields' => 'id,title,image_id,description'])->json();
          $this->imageBaseURL = $response['config']['iiif_url'] ?? 'https://www.artic.edu/iiif/2';
          $records = collect($response['data'])
            ->filter(fn($item) => !empty($item['image_id']))
            ->map(function ($item) {
              $item['image_url'] = $this->getImageURL($item['image_id']);
              $item['is_favorited'] = $this->checkIsFavorite($item['image_id']);
              return $item;
            })
            ->when(
              filled($sortColumn),
              fn(Collection $data) => $data->sortBy($sortColumn, SORT_REGULAR, $sortDirection === 'desc')
            );
          return new LengthAwarePaginator(
            $records,
            $response['pagination']['total'],
            $recordsPerPage,
            $page
          );
        }
      )
      ->columns([
        Grid::make()
          ->columns(1)
          ->schema([
            TextColumn::make('title')
              ->label('Title')
              ->getStateUsing(fn($record): mixed => Str::limit($record['title'] ?? 'Untitled', 30))
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
      ->paginationPageOptions([12, 24, 48, 60, 100])
      ->recordActions([
        Action::make('toggleFavorite')
          ->label('')
          ->icon(fn($record) => $record['is_favorited'] ? 'heroicon-s-heart' : 'heroicon-o-heart')
          ->color(fn($record) => $record['is_favorited'] ? 'danger' : 'gray')
          ->action(function ($record) {
            $isFavorited = FavoriteService::toggle($record, 'api');
            $record['is_favorited'] = $isFavorited;
          }),
        Action::make('downloadImage')
          ->label('')
          ->icon('heroicon-o-arrow-down-tray')
          ->action(fn($record) => DownloadService::download($record, 'api')),
      ]);
  }
}
