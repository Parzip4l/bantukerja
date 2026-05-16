<?php

namespace App\Services;

class ToolCalculationService
{
    public function calculateThr(float $monthlySalary, int $monthsWorked, ?string $employeeStatus = null): array
    {
        $multiplier = $monthsWorked >= 12 ? 1 : ($monthsWorked / 12);
        $thr = $monthlySalary * $multiplier;

        return [
            'monthly_salary' => $monthlySalary,
            'months_worked' => $monthsWorked,
            'employee_status' => $employeeStatus,
            'multiplier' => round($multiplier, 4),
            'thr_amount' => round($thr, 2),
            'explanation' => $monthsWorked >= 12
                ? 'Masa kerja sudah 12 bulan atau lebih, sehingga THR setara 1 kali gaji bulanan.'
                : 'Masa kerja di bawah 12 bulan, sehingga THR dihitung proporsional: masa kerja / 12 x gaji bulanan.',
        ];
    }

    public function calculateNetSalary(
        float $baseSalary,
        float $allowance,
        float $deduction,
        float $bpjs = 0,
        float $tax = 0,
    ): array {
        $totalIncome = $baseSalary + $allowance;
        $totalDeductions = $deduction + $bpjs + $tax;

        return [
            'base_salary' => $baseSalary,
            'allowance' => $allowance,
            'deduction' => $deduction,
            'bpjs' => $bpjs,
            'tax' => $tax,
            'total_income' => round($totalIncome, 2),
            'total_deductions' => round($totalDeductions, 2),
            'net_salary' => round($totalIncome - $totalDeductions, 2),
        ];
    }

    public function calculateOvertime(float $monthlyWage, float $hours, string $dayType): array
    {
        $hourlyWage = $monthlyWage / 173;
        $multiplier = $dayType === 'libur' ? 2 : 1.5;
        $total = $hourlyWage * $hours * $multiplier;

        return [
            'monthly_wage' => $monthlyWage,
            'hours' => $hours,
            'day_type' => $dayType,
            'hourly_wage' => round($hourlyWage, 2),
            'multiplier' => $multiplier,
            'overtime_total' => round($total, 2),
            'note' => 'Perhitungan ini adalah estimasi sederhana dan bukan nasihat hukum atau payroll final.',
        ];
    }

    public function calculateInvoiceTotal(array $items, float $tax = 0, float $discount = 0): array
    {
        $normalizedItems = collect($items)
            ->filter(fn (array $item): bool => filled($item['name'] ?? null))
            ->map(function (array $item): array {
                $qty = (float) ($item['qty'] ?? 0);
                $price = (float) ($item['price'] ?? 0);

                return [
                    'name' => trim((string) ($item['name'] ?? '')),
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => round($qty * $price, 2),
                ];
            })
            ->values();

        $subtotal = $normalizedItems->sum('subtotal');
        $taxAmount = $subtotal * ($tax / 100);
        $discountAmount = $subtotal * ($discount / 100);
        $grandTotal = $subtotal + $taxAmount - $discountAmount;

        return [
            'items' => $normalizedItems->all(),
            'subtotal' => round($subtotal, 2),
            'tax_percentage' => $tax,
            'tax_amount' => round($taxAmount, 2),
            'discount_percentage' => $discount,
            'discount_amount' => round($discountAmount, 2),
            'grand_total' => round($grandTotal, 2),
        ];
    }
}
