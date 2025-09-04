<?php

namespace App\Filament\Resources\Favorites\Tables;

use App\Models\Favorite;
use App\Services\FavoriteService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FavoritesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        Grid::make()
          ->columns(1)
          ->schema([
            TextColumn::make('display_title')
              ->label('Title')
              ->getStateUsing(fn($record) => Str::limit($record->display_title ?? 'Untitled', 30))
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
      ->recordActions([
        Action::make('toggleFavorite')
          ->label('')
          ->icon(fn(Favorite $record) => $record->is_favorited ? 'heroicon-s-heart' : 'heroicon-o-heart')
          ->color(fn(Favorite $record) => $record->is_favorited ? 'danger' : 'gray')
          ->action(function (Favorite $record) {
            $isFavorited = FavoriteService::toggle($record, $record->source);
            $record->is_favorited = $isFavorited;
            $record->save();
          }),

        DeleteAction::make()->label(''),
      ]);
  }
}
