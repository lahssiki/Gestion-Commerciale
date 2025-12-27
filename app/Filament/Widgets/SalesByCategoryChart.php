<?php
namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\InvoiceItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Ventes par Catégorie';
    protected static ?int $sort = 5;

    public ?string $filter = 'month';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = InvoiceItem::query()
            ->join('products', 'invoice_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.status', 'paid')
            ->select('categories.name', DB::raw('SUM(invoice_items.total) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc');

        if ($activeFilter === 'week') {
            $query->whereBetween('invoices.date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($activeFilter === 'month') {
            $query->whereMonth('invoices.date', now()->month)
                  ->whereYear('invoices.date', now()->year);
        } elseif ($activeFilter === 'year') {
            $query->whereYear('invoices.date', now()->year);
        }

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Ventes (MAD)',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Cette Semaine',
            'month' => 'Ce Mois',
            'year' => 'Cette Année',
        ];
    }
}