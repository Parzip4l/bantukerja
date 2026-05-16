<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\EditContactMessage;
use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Schemas\ContactMessageForm;
use App\Filament\Resources\ContactMessages\Tables\ContactMessagesTable;
use App\Models\ContactMessage;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|\UnitEnum|null $navigationGroup = 'Konfigurasi';

    public static function form(Schema $schema): Schema
    {
        return ContactMessageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactMessagesTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return self::contentManager();
    }

    public static function canEdit($record): bool
    {
        return self::contentManager();
    }

    public static function canDelete($record): bool
    {
        return self::adminOnly();
    }

    protected static function contentManager(): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return $user instanceof User && in_array($user->role, ['admin', 'editor'], true);
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
            'index' => ListContactMessages::route('/'),
            'edit' => EditContactMessage::route('/{record}/edit'),
        ];
    }
}
