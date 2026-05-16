<?php

namespace App\Filament\Resources\GeneratorTemplates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GeneratorTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Textarea::make('description')->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Generator Configuration')
                ->schema([
                    Select::make('generator_type')
                        ->options([
                            'invoice' => 'Invoice',
                            'letter' => 'Letter',
                            'receipt' => 'Receipt',
                            'minutes' => 'Minutes',
                            'statement' => 'Statement',
                            'all' => 'All',
                        ])
                        ->required(),
                    TextInput::make('view_path')
                        ->required()
                        ->helperText('Contoh: generators.invoice.templates.classic'),
                    Select::make('paper_size')
                        ->options([
                            'a4' => 'A4',
                            'letter' => 'Letter',
                        ])
                        ->default('a4')
                        ->required(),
                    Select::make('orientation')
                        ->options([
                            'portrait' => 'Portrait',
                            'landscape' => 'Landscape',
                        ])
                        ->default('portrait')
                        ->required(),
                    KeyValue::make('settings')
                        ->keyLabel('Key')
                        ->valueLabel('Value')
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Preview & Display')
                ->schema([
                    FileUpload::make('preview_image')
                        ->disk('uploads')
                        ->directory('generator-templates/previews')
                        ->image()
                        ->visibility('public')
                        ->imageEditor()
                        ->columnSpanFull(),
                    TextInput::make('sort_order')->numeric()->default(0),
                ])
                ->columns(2),
            Section::make('Publishing')
                ->schema([
                    Toggle::make('is_active')->default(true),
                    Toggle::make('is_premium')->default(false),
                ])
                ->columns(2),
        ]);
    }
}
