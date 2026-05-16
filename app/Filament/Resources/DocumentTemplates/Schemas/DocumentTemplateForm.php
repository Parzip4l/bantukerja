<?php

namespace App\Filament\Resources\DocumentTemplates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class DocumentTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi utama')
                ->schema([
                    Select::make('category_id')->relationship('category', 'name')->searchable()->preload(),
                    TextInput::make('title')->required()->live(onBlur: true)->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Textarea::make('short_description')->required()->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Konten')
                ->schema([
                    RichEditor::make('content')->required()->columnSpanFull(),
                    Repeater::make('faqs')
                        ->relationship()
                        ->schema([
                            TextInput::make('question')->required(),
                            Textarea::make('answer')->required()->rows(3),
                            TextInput::make('sort_order')->numeric()->default(0),
                            Toggle::make('is_active')->default(true),
                        ])
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),
            Section::make('SEO')
                ->schema([
                    TextInput::make('meta_title')->required(),
                    Textarea::make('meta_description')->required()->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Publishing')
                ->schema([
                    Toggle::make('is_featured')->default(false),
                    Toggle::make('is_published')->default(true),
                    DateTimePicker::make('published_at'),
                ])
                ->columns(3),
        ]);
    }
}
