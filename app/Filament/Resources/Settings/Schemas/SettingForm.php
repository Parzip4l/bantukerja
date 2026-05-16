<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Setting')
                ->schema([
                    TextInput::make('key')->required()->unique(ignoreRecord: true),
                    TextInput::make('group'),
                    Textarea::make('value')->rows(8)->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
