<?php

namespace App\Filament\Resources\Favorites\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FavoriteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('source')
                    ->required(),
                FileUpload::make('image_id')
                    ->image(),
                FileUpload::make('api_image_id')
                    ->image(),
            ]);
    }
}
