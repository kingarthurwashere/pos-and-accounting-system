<?php

namespace App\Filament\Resources\RefundMethodResource\Pages;

use App\Filament\Resources\RefundMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefundMethod extends EditRecord
{
    protected static string $resource = RefundMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
