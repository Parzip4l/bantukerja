<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi kategori')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Select::make('type')
                        ->options([
                            'blog' => 'Blog',
                            'tool' => 'Tool',
                            'template' => 'Template',
                        ])
                        ->required(),
                    Toggle::make('is_active')->default(true),
                    Textarea::make('description')->rows(4)->columnSpanFull(),
                    TextInput::make('meta_title')->maxLength(255),
                    Textarea::make('meta_description')->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
