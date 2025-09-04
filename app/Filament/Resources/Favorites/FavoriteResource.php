<?php

namespace App\Filament\Resources\Favorites;

use App\Filament\Resources\Favorites\Pages\ListFavorites;
use App\Filament\Resources\Favorites\Schemas\FavoriteForm;
use App\Filament\Resources\Favorites\Tables\FavoritesTable;
use App\Models\Favorite;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FavoriteResource extends Resource
{
  protected static ?string $model = Favorite::class;

  protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

  protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Heart;

  protected static ?int $navigationSort = 3;

  protected static ?string $recordTitleAttribute = 'Favorite';

  public static function form(Schema $schema): Schema
  {
    return FavoriteForm::configure($schema);
  }

  public static function table(Table $table): Table
  {
    return FavoritesTable::configure($table);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => ListFavorites::route('/'),
    ];
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()->with('image');
  }
}
