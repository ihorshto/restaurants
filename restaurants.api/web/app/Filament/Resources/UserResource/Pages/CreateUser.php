<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        // Якщо не призначено жодної ролі, призначаємо restaurant_admin за замовчуванням
        if (! $user->hasAnyRole(['super_admin', 'restaurant_admin'])) {
            $user->assignRole('restaurant_admin');
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Користувача успішно створено';
    }
}
