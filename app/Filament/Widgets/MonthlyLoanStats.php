<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Loan;
use App\Models\Installment;
use Carbon\Carbon;

class MonthlyLoanStats extends BaseWidget
{
    protected function getCards(): array
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return [
            // 🔹 **All-Time Statistics (Total Overview)**
            Card::make('🔹 کۆی قەرزەکان', $this->getLoanCount() . ' قەرز')
                ->description('هەموو قەرزەکان تاکو ئێستا')
                ->icon('heroicon-o-currency-dollar')
                ->color('primary')
                ->extraAttributes(['class' => 'text-lg p-6']) // Bigger card size
                ->chart($this->getLoanTrends()),

            Card::make('💰 کۆی پارەی دراو بە (IQD)', number_format(
                Loan::where('currency', 'IQD')->sum('down_payment') + Loan::where('currency', 'IQD')->sum('returned_money')
            ) . ' IQD')
                ->description('کۆی پارەی دراو تاکو ئێستا')
                ->icon('heroicon-o-banknotes')  
                ->color('success')
                ->extraAttributes(['class' => 'text-lg p-6'])
                ->chart($this->getInstallmentTrends('IQD')),

            Card::make('💵 کۆی پارەی دراو بە ($)', '$' . number_format(
                Loan::where('currency', 'USD')->sum('down_payment') + Loan::where('currency', 'USD')->sum('returned_money'),
                2
            ))
                ->description('کۆی پارەی دراو تاکو ئێستا')
                ->icon('heroicon-o-banknotes')
                ->color('warning')
                ->extraAttributes(['class' => 'text-lg p-6'])
                ->chart($this->getInstallmentTrends('USD')),

            Card::make('💳 کۆی قەرزی نەدراو (IQD)', number_format(
                Loan::where('currency', 'IQD')->sum('outstanding_balance')
            ) . ' IQD')
                ->description('کۆی ماوەی قەرز تاکو ئێستا')
                ->icon('heroicon-o-scale')
                ->color('danger')
                ->extraAttributes(['class' => 'text-lg p-6'])
                ->chart($this->getOutstandingBalanceTrends('IQD')),

            Card::make('💳 کۆی قەرزی نەدراو ($)', '$' . number_format(
                Loan::where('currency', 'USD')->sum('outstanding_balance'),
                2
            ))
                ->description('کۆی ماوەی قەرز تاکو ئێستا')
                ->icon('heroicon-o-scale')
                ->color('danger')
                ->extraAttributes(['class' => 'text-lg p-6'])
                ->chart($this->getOutstandingBalanceTrends('USD')),

            // 🔹 **Current Month Statistics (Separate Section)**
            Card::make('📅 قەرزە نوێیەکانی ئەم مانگە', Loan::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)->count() . ' قەرز')
                ->description('تەنها بۆ ئەم مانگە')
                ->icon('heroicon-o-currency-dollar')
                ->color('info')
                ->extraAttributes(['class' => 'text-lg p-6 border-t-4 border-gray-400']) // Separate Monthly section
                ->chart($this->getCurrentMonthLoanTrends()),

            Card::make('📅 کۆی پارەی دراو بە (IQD) لەم مانگە', number_format(
                Loan::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->where('currency', 'IQD')
                    ->sum('down_payment') + Loan::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->where('currency', 'IQD')
                    ->sum('returned_money')
            ) . ' IQD')
                ->description('کۆی پارەی دراو لەم مانگە')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->extraAttributes(['class' => 'text-lg p-6 border-t-4 border-gray-400'])
                ->chart($this->getCurrentMonthInstallmentTrends('IQD')),

            Card::make('📅 کۆی پارەی دراو بە ($) لەم مانگە', '$' . number_format(
                Loan::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->where('currency', 'USD')
                    ->sum('down_payment') + Installment::whereMonth('paid_date', $currentMonth)
                    ->whereYear('paid_date', $currentYear)
                    ->whereHas('loan', fn($query) => $query->where('currency', 'USD'))
                    ->sum('amount'),
                2
            ))
                ->description('کۆی پارەی دراو لەم مانگە')
                ->icon('heroicon-o-banknotes')
                ->color('warning')
                ->extraAttributes(['class' => 'text-lg p-6 border-t-4 border-gray-400'])
                ->chart($this->getCurrentMonthInstallmentTrends('USD')),

            Card::make('📅  قەرزە نەدراوەکانی ئەم مانگە بە (IQD)', number_format(
                Loan::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->where('currency', 'IQD')->sum('outstanding_balance')
            ) . ' IQD')
                ->description('کۆی ماوەی قەرزەکان لەم مانگە')
                ->icon('heroicon-o-scale')
                ->color('danger')
                ->extraAttributes(['class' => 'text-lg p-6 border-t-4 border-gray-400'])
                ->chart($this->getCurrentMonthOutstandingBalanceTrends('IQD')),

            Card::make('📅 قەرزە نەدراوەکانی ئەم مانگە بە ($)', '$' . number_format(
                Loan::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->where('currency', 'USD')->sum('outstanding_balance'),
                2
            ))
                ->description('کۆی ماوەی قەرزەکان لەم مانگە')
                ->icon('heroicon-o-scale')
                ->color('danger')
                ->extraAttributes(['class' => 'text-lg p-6 border-t-4 border-gray-400'])
                ->chart($this->getCurrentMonthOutstandingBalanceTrends('USD')),
        ];
    }


    protected function getLoanCount(): int
    {
        $count = Loan::whereNot('status', 'completed')->count();
        return $count;
    }

    // 📊 **Last 6 Months Loan Trends**
    protected function getLoanTrends(): array
    {
        return $this->getTrends('loans', 'created_at');
    }

    // 📊 **Last 6 Months Installment Trends**
    protected function getInstallmentTrends(string $currency): array
    {
        return $this->getTrends('installments', 'paid_date', $currency);
    }

    // 📊 **Last 6 Months Outstanding Balance Trends**
    protected function getOutstandingBalanceTrends(string $currency): array
    {
        return $this->getTrends('loans', 'created_at', $currency, true);
    }

    // 📊 **Generate Daily Loan Trends for the Current Month**
    protected function getCurrentMonthLoanTrends(): array
    {
        return $this->getDailyTrends('loans', 'created_at');
    }

    // 📊 **Generate Daily Installment Trends for the Current Month**
    protected function getCurrentMonthInstallmentTrends(string $currency): array
    {
        return $this->getDailyTrends('installments', 'paid_date', $currency);
    }

    // 📊 **Generate Daily Outstanding Balance Trends for the Current Month**
    protected function getCurrentMonthOutstandingBalanceTrends(string $currency): array
    {
        return $this->getDailyTrends('loans', 'created_at', $currency, true);
    }

    // 🔹 **Helper Function: Get Daily Trends for the Current Month**
    protected function getDailyTrends(string $table, string $dateField, string $currency = null, bool $sumBalance = false): array
    {
        $trends = [];
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            if ($table === 'loans') {
                $query = Loan::whereYear($dateField, $currentYear)
                    ->whereMonth($dateField, $currentMonth)
                    ->whereDay($dateField, $day);

                if ($currency) {
                    $query->where('currency', $currency);
                }

                $trends[] = $sumBalance ? $query->sum('outstanding_balance') : $query->count();
            } else {
                $query = Installment::whereYear($dateField, $currentYear)
                    ->whereMonth($dateField, $currentMonth)
                    ->whereDay($dateField, $day);

                if ($currency) {
                    $query->whereHas('loan', fn($q) => $q->where('currency', $currency));
                }

                $trends[] = $query->sum('amount');
            }
        }

        return $trends;
    }

    // 🔹 **Helper Functions for Trends**
    protected function getTrends(string $table, string $dateField, string $currency = null, bool $sumBalance = false): array
    {
        $trends = [];
        $current = Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $month = $current->subMonth()->month;
            $year = $current->year;

            if ($table === 'loans') {
                $query = Loan::whereMonth($dateField, $month)->whereYear($dateField, $year);
                if ($currency) $query->where('currency', $currency);
                $trends[] = $sumBalance ? $query->sum('outstanding_balance') : $query->count();
            } else {
                $query = Installment::whereMonth($dateField, $month)->whereYear($dateField, $year);
                if ($currency) $query->whereHas('loan', fn($q) => $q->where('currency', $currency));
                $trends[] = $query->sum('amount');
            }
        }

        return $trends;
    }

    protected function getCurrentMonthCount(string $table, string $dateField, string $currency = null, bool $sumBalance = false): int
    {
        $query = $table === 'loans' ? Loan::whereMonth($dateField, Carbon::now()->month) : Installment::whereMonth($dateField, Carbon::now()->month);
        if ($currency) {
            if ($table === 'loans') {
                $query->where('currency', $currency);
            } else {
                $query->whereHas('loan', fn($q) => $q->where('currency', $currency));
            }
        }
        return $sumBalance ? $query->sum('outstanding_balance') : $query->count();
    }
}
