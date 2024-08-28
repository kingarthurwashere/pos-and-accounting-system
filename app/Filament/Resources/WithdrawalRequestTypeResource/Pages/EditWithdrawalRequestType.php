<?php

namespace App\Filament\Resources\WithdrawalRequestTypeResource\Pages;

use App\Filament\Resources\WithdrawalRequestTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawalRequestType extends EditRecord
{
    protected static string $resource = WithdrawalRequestTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
