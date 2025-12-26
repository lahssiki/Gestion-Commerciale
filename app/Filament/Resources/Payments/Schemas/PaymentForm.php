<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoice_id')
                    ->relationship('invoice', 'id')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Select::make('method')
                    ->options(['cash' => 'Cash', 'card' => 'Card', 'transfer' => 'Transfer', 'check' => 'Check'])
                    ->required(),
                DatePicker::make('date')
                    ->required(),
            ]);
    }
}
