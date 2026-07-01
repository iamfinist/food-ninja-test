<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers\ClicksRelationManager;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $recordTitleAttribute = 'code';

    public static function getModelLabel(): string
    {
        return __('messages.links.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.links.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.links.navigation');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('original_url')
                    ->label(__('messages.links.original_url'))
                    ->url()
                    ->required()
                    ->maxLength(2048)
                    ->placeholder('https://example.com/page')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('messages.links.short_url'))
                    ->formatStateUsing(fn (Link $record): string => $record->shortUrl())
                    ->copyable()
                    ->copyableState(fn (Link $record): string => $record->shortUrl())
                    ->copyMessage(__('messages.links.copied'))
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where(
                        'code',
                        'like',
                        '%' . str($search)->afterLast('/') . '%'
                    )),
                Tables\Columns\TextColumn::make('original_url')
                    ->label(__('messages.links.original_url'))
                    ->limit(50)
                    ->url(fn (Link $record): string => $record->original_url, shouldOpenInNewTab: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('clicks_count')
                    ->label(__('messages.links.clicks'))
                    ->counts('clicks')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('messages.links.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('messages.links.empty'))
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
