<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
public static function configure(Table $table): Table
{
    return $table
        ->defaultSort('created_at', 'desc')
        ->columns([
            TextColumn::make('name')
                ->searchable()
                ->sortable(),

            TextColumn::make('phone')
                ->searchable(),

            TextColumn::make('email')
                ->label('Email')
                ->searchable(),

            TextColumn::make('created_at')
                ->label('Created')
                ->date('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label('Updated')
                ->date('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->recordActions([
            EditAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
        ->emptyStateHeading('No clients found')
        ->emptyStateDescription('Start by creating a new client.');
}

}
