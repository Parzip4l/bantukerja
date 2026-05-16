<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi artikel')
                ->schema([
                    Select::make('category_id')->relationship('category', 'name')->searchable()->preload(),
                    TextInput::make('title')->required()->live(onBlur: true)->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Select::make('status')->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])->required(),
                    Textarea::make('excerpt')->required()->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Konten')
                ->schema([
                    RichEditor::make('content')->required()->columnSpanFull(),
                ]),
            Section::make('Media')
                ->description('Upload gambar artikel dan OG image langsung dari panel admin.')
                ->schema([
                    FileUpload::make('featured_image')
                        ->label('Featured image')
                        ->disk('public')
                        ->directory('posts/featured-images')
                        ->image()
                        ->imageEditor()
                        ->visibility('public')
                        ->columnSpan(1),
                    FileUpload::make('og_image')
                        ->label('OG image')
                        ->disk('public')
                        ->directory('posts/og-images')
                        ->image()
                        ->imageEditor()
                        ->visibility('public')
                        ->columnSpan(1),
                ])
                ->columns(2),
            Section::make('FAQ')
                ->description('Pertanyaan umum untuk memperkaya SEO dan membantu pembaca.')
                ->schema([
                    Repeater::make('faqs')
                        ->relationship()
                        ->schema([
                            TextInput::make('question')->required(),
                            Textarea::make('answer')->required()->rows(3),
                            TextInput::make('sort_order')->numeric()->default(0),
                            Toggle::make('is_active')->default(true),
                        ])
                        ->defaultItems(0)
                        ->addActionLabel('Tambah FAQ')
                        ->grid(2)
                        ->columnSpanFull(),
                ]),
            Section::make('SEO')
                ->schema([
                    TextInput::make('meta_title')->required(),
                    Textarea::make('meta_description')->required()->rows(3)->columnSpanFull(),
                    DateTimePicker::make('published_at'),
                ])
                ->columns(2),
        ]);
    }
}
