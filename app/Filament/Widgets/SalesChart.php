<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Symfony\Component\Clock\now;
use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected ?string $heading = 'Ventes Mensuelles';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'year';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = match ($activeFilter) {
            'today' => $this->getTodayData(),
            'week' => $this->getWeekData(),
            'month' => $this->getMonthData(),
            'year' => $this->getYearData(),
            default => $this->getYearData(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Ventes (MAD)',
                    'data' => $data['values'],
                    'backgroundColor' => 'rgba(102, 126, 234, 0.1)',
                    'borderColor' => 'rgba(102, 126, 234, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => "Aujourd'hui",
            'week' => 'Cette Semaine',
            'month' => 'Ce Mois',
            'year' => 'Cette Année',
        ];
    }

    private function getTodayData(): array
    {
        $data = Invoice::whereDate('date', now()->toDateString())
            ->where('status', 'paid')
            ->selectRaw('HOUR(created_at) as hour, SUM(total_ttc) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $labels = [];
        $values = [];

        for ($i = 0; $i < 24; $i++) {
            $labels[] = $i . 'h';
            $hourData = $data->firstWhere('hour', $i);
            $values[] = $hourData ? $hourData->total : 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getWeekData(): array
    {
        $data = Invoice::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'paid')
            ->selectRaw('DATE(date) as day, SUM(total_ttc) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $labels = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $values = [];

        for ($i = 0; $i < 7; $i++) {
            $day = now()->startOfWeek()->addDays($i)->format('Y-m-d');
            $dayData = $data->firstWhere('day', $day);
            $values[] = $dayData ? $dayData->total : 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getMonthData(): array
    {
        $data = Invoice::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'paid')
            ->selectRaw('DAY(date) as day, SUM(total_ttc) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $daysInMonth = now()->daysInMonth;
        $labels = [];
        $values = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = $i;
            $dayData = $data->firstWhere('day', $i);
            $values[] = $dayData ? $dayData->total : 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getYearData(): array
    {
        $data = Invoice::whereYear('date', now()->year)
            ->where('status', 'paid')
            ->selectRaw('MONTH(date) as month, SUM(total_ttc) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $values = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $data->firstWhere('month', $i);
            $values[] = $monthData ? $monthData->total : 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }
}