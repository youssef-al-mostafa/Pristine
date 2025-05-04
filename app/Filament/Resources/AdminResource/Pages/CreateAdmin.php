<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Enums\RolesEnum;
use App\Filament\Resources\AdminResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole(RolesEnum::ADMIN->value);
        $this->record->sendEmailVerificationNotification();
    }
}
