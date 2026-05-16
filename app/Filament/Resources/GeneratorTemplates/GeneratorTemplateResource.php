<?php

namespace App\Filament\Resources\GeneratorTemplates;

use App\Filament\Resources\GeneratorTemplates\Pages\CreateGeneratorTemplate;
use App\Filament\Resources\GeneratorTemplates\Pages\EditGeneratorTemplate;
use App\Filament\Resources\GeneratorTemplates\Pages\ListGeneratorTemplates;
use App\Filament\Resources\GeneratorTemplates\Schemas\GeneratorTemplateForm;
use App\Filament\Resources\GeneratorTemplates\Tables\GeneratorTemplatesTable;
use App\Models\GeneratorTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneratorTemplateResource extends Resource
{
    protected static ?string $model = GeneratorTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|\UnitEnum|null $navigationGroup = 'Generator';

    public static function form(Schema $schema): Schema
    {
        return GeneratorTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GeneratorTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGeneratorTemplates::route('/'),
            'create' => CreateGeneratorTemplate::route('/create'),
            'edit' => EditGeneratorTemplate::route('/{record}/edit'),
        ];
    }
}
