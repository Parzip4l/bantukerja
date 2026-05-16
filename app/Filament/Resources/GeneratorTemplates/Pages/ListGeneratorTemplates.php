<?php

namespace App\Filament\Resources\GeneratorTemplates\Pages;

use App\Filament\Resources\GeneratorTemplates\GeneratorTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeneratorTemplates extends ListRecords
{
    protected static string $resource = GeneratorTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
