<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;

class Favorites extends Page
{
    protected string $view = 'filament.pages.favorites';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-heart';
    protected static string | BackedEnum | null $activeNavigationIcon = 'heroicon-s-heart';
    protected static ?int $navigationSort = 3;
}
