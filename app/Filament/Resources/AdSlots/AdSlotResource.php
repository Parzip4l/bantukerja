<?php

namespace App\Filament\Resources\AdSlots;

use App\Filament\Resources\AdSlots\Pages\CreateAdSlot;
use App\Filament\Resources\AdSlots\Pages\EditAdSlot;
use App\Filament\Resources\AdSlots\Pages\ListAdSlots;
use App\Filament\Resources\AdSlots\Schemas\AdSlotForm;
use App\Filament\Resources\AdSlots\Tables\AdSlotsTable;
use App\Models\AdSlot;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdSlotResource extends Resource
{
    protected static ?string $model = AdSlot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|\UnitEnum|null $navigationGroup = 'Konfigurasi';

    public static function form(Schema $schema): Schema
    {
        return AdSlotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdSlotsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        return self::adminOnly();
    }

    public static function canCreate(): bool
    {
        return self::adminOnly();
    }

    public static function canEdit($record): bool
    {
        return self::adminOnly();
    }

    public static function canDelete($record): bool
    {
        return self::adminOnly();
    }

    protected static function adminOnly(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user instanceof User && $user->role === 'admin';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdSlots::route('/'),
            'create' => CreateAdSlot::route('/create'),
            'edit' => EditAdSlot::route('/{record}/edit'),
        ];
    }
}
