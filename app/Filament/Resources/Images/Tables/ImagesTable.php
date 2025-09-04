<?php

namespace App\Filament\Resources\Images\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ImagesTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->recordUrl(null)
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
      ->recordActions([
        EditAction::make(),
      ]);
  }
}
