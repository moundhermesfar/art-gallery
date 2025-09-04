<?php

namespace App\Filament\Resources\Images\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255)
                    ->required(),
                Textarea::make('description')
                    ->rows(3)
                    ->required(),
                FileUpload::make('path')
                    ->label('Upload Image')
                    ->disk('public')
                    ->directory('user-images')
                    ->image()
                    ->maxSize(1024 * 1024 * 6)
                    ->visibility('public')
                    ->required(),
                Toggle::make('is_favorite')
                    ->label('Mark as Favorite')
                    ->required(),
            ]);
    }
}
