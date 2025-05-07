<?php

namespace App\Filament\User\Resources\CupomResource\Pages;

use App\Filament\User\Resources\CupomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCupom extends EditRecord
{
    protected static string $resource = CupomResource::class;

    public $loadData = true;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
