<?php

namespace App\Filament\Resources\AdSlots\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdSlotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Slot iklan')
                ->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('key')->required()->unique(ignoreRecord: true),
                    TextInput::make('location')->required(),
                    Toggle::make('is_active')->default(true),
                    Textarea::make('code')->rows(10)->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
