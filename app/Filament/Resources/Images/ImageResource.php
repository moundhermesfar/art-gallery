<?php

namespace App\Filament\Resources\Images;

use App\Filament\Resources\Images\Pages\CreateImage;
use App\Filament\Resources\Images\Pages\EditImage;
use App\Filament\Resources\Images\Pages\ListImages;
use App\Filament\Resources\Images\Pages\ViewImage;
use App\Filament\Resources\Images\Schemas\ImageForm;
use App\Filament\Resources\Images\Schemas\ImageInfolist;
use App\Filament\Resources\Images\Tables\ImagesTable;
use App\Models\Image;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Photo;
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'Image';
    protected static ?string $navigationLabel = 'My Images';

    public static function form(Schema $schema): Schema
    {
        return ImageForm::configure($schema);
    }


    public static function table(Table $table): Table
    {
        return ImagesTable::configure($table);
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
            'index' => ListImages::route('/'),
            'create' => CreateImage::route('/create'),
            'edit' => EditImage::route('/{record}/edit'),
        ];
    }
}
