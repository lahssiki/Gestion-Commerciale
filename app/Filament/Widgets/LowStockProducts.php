<?php
namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockProducts extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->whereColumn('stock', '<=', 'stock_alert')
                    ->orderBy('stock', 'asc')
            )
            ->heading('⚠️ Produits en Stock Faible')
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('Référence')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock Actuel')
                    ->badge()
                    ->color('danger')
                    ->suffix(' unités'),
                Tables\Columns\TextColumn::make('stock_alert')
                    ->label('Seuil Alerte')
                    ->badge()
                    ->color('warning')
                    ->suffix(' unités'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('MAD'),
            ]);
    }
}