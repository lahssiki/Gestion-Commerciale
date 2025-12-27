<?php
namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculer les revenus du mois
        $currentMonthRevenue = Invoice::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'paid')
            ->sum('total_ttc');

        $lastMonthRevenue = Invoice::whereMonth('date', now()->subMonth()->month)
            ->whereYear('date', now()->subMonth()->year)
            ->where('status', 'paid')
            ->sum('total_ttc');

        $revenueIncrease = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;

        // Factures en attente
        $pendingInvoices = Invoice::whereIn('status', ['draft', 'sent'])->count();
        
        // Factures payées ce mois
        $paidInvoicesThisMonth = Invoice::whereMonth('date', now()->month)
            ->where('status', 'paid')
            ->count();

        // Nouveaux clients ce mois
        $newClientsThisMonth = Client::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Produits en stock faible
        $lowStockProducts = Product::whereColumn('stock', '<=', 'stock_alert')->count();

        return [
            Stat::make('Revenus du Mois', number_format($currentMonthRevenue, 2) . ' MAD')
                ->description($revenueIncrease >= 0 ? '+' . number_format($revenueIncrease, 1) . '% par rapport au mois dernier' : number_format($revenueIncrease, 1) . '% par rapport au mois dernier')
                ->descriptionIcon($revenueIncrease >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueIncrease >= 0 ? 'success' : 'danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Factures en Attente', $pendingInvoices)
                ->description($paidInvoicesThisMonth . ' factures payées ce mois')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Nouveaux Clients', $newClientsThisMonth)
                ->description('Ce mois-ci')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('Alertes Stock', $lowStockProducts)
                ->description('Produits en stock faible')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockProducts > 0 ? 'danger' : 'success'),
        ];
    }
}