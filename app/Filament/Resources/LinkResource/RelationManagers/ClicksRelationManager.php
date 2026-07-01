<?php

namespace App\Filament\Resources\LinkResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('messages.links.stats.title'))
            ->recordTitleAttribute('ip_address')
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('messages.links.stats.ip'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.links.stats.datetime'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('messages.links.stats.empty'))
            ->paginated([10, 25, 50])
            ->actions([])
            ->bulkActions([]);
    }
}
