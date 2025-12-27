<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->searchable(),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('reference')
                ->label('Reference / SKU')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(100),

            TextInput::make('price')
                ->numeric()
                ->required()
                ->prefix('MAD'),
        ]);
    }
}
