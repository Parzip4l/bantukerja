<?php

namespace App\Filament\Resources\GeneratorTemplates\Pages;

use App\Filament\Resources\GeneratorTemplates\GeneratorTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGeneratorTemplate extends EditRecord
{
    protected static string $resource = GeneratorTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
