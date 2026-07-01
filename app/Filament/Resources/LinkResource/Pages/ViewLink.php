<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use App\Models\Link;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewLink extends ViewRecord
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('code')
                    ->label(__('messages.links.short_url'))
                    ->state(fn (Link $record): string => $record->shortUrl())
                    ->copyable()
                    ->copyMessage(__('messages.links.copied')),
                TextEntry::make('original_url')
                    ->label(__('messages.links.original_url'))
                    ->url(fn (Link $record): string => $record->original_url, shouldOpenInNewTab: true),
                TextEntry::make('clicks_count')
                    ->label(__('messages.links.total_clicks'))
                    ->state(fn (Link $record): int => $record->clicks()->count())
                    ->badge(),
                TextEntry::make('created_at')
                    ->label(__('messages.links.created_at'))
                    ->dateTime(),
            ]);
    }
}
