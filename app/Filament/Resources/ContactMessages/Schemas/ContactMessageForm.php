<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pesan masuk')
                ->schema([
                    Placeholder::make('name')->label('Nama')->content(fn ($record) => $record?->name),
                    Placeholder::make('email')->label('Email')->content(fn ($record) => $record?->email),
                    Placeholder::make('subject')->label('Subjek')->content(fn ($record) => $record?->subject),
                    Placeholder::make('ip_address')->label('IP')->content(fn ($record) => $record?->ip_address ?: '-'),
                    Select::make('status')
                        ->options([
                            'new' => 'New',
                            'read' => 'Read',
                            'resolved' => 'Resolved',
                        ])
                        ->required(),
                    Textarea::make('message')
                        ->rows(10)
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
