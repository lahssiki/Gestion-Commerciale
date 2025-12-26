<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('invoice_number')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('total_ht')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tva')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_ttc')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'sent' => 'Sent', 'paid' => 'Paid', 'cancelled' => 'Cancelled'])
                    ->default('draft')
                    ->required(),
            ]);
    }
}
