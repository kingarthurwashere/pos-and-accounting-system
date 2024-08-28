<?php

namespace App\Filament\Resources\WithdrawalMethodResource\Pages;

use App\Filament\Resources\WithdrawalMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawalMethod extends EditRecord
{
    protected static string $resource = WithdrawalMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
