<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman')
                ->schema([
                    TextInput::make('title')->required()->live(onBlur: true)->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Toggle::make('is_published')->default(true),
                    RichEditor::make('content')->required()->columnSpanFull(),
                    TextInput::make('meta_title'),
                    Textarea::make('meta_description')->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
