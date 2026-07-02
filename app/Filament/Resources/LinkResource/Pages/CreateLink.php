<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Actions\CreateShortLink;
use App\Filament\Resources\LinkResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateLink extends CreateRecord
{
    protected static string $resource = LinkResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateShortLink::class)->execute(Auth::user(), $data['original_url']);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Короткая ссылка создана';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
