<?php

namespace App\Filament\Resources\GeneratorTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GeneratorTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('generator_type')->badge()->sortable(),
                TextColumn::make('slug')->searchable(),
                IconColumn::make('is_active')->boolean(),
                IconColumn::make('is_premium')->boolean(),
                TextColumn::make('sort_order')->sortable(),
                TextColumn::make('updated_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('generator_type')
                    ->options([
                        'invoice' => 'Invoice',
                        'letter' => 'Letter',
                        'receipt' => 'Receipt',
                        'minutes' => 'Minutes',
                        'statement' => 'Statement',
                        'all' => 'All',
                    ]),
                TernaryFilter::make('is_active'),
                TernaryFilter::make('is_premium'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
