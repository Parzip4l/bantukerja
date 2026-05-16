<?php

namespace App\Services;

use Illuminate\Support\Str;

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
                    'description' => trim((string) ($item['description'] ?? '')),
                    'unit' => trim((string) ($item['unit'] ?? '')),
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

    public function prepareCvAtsData(array $data): array
    {
        $skills = collect(preg_split('/[\r\n,]+/', (string) ($data['skills'] ?? '')))
            ->map(fn (?string $skill): string => trim((string) $skill))
            ->filter()
            ->values()
            ->all();

        $languages = collect(preg_split('/[\r\n,]+/', (string) ($data['languages'] ?? '')))
            ->map(fn (?string $language): string => trim((string) $language))
            ->filter()
            ->values()
            ->all();

        $certifications = collect(preg_split('/\r\n|\r|\n/', (string) ($data['certifications'] ?? '')))
            ->map(fn (?string $item): string => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        $achievements = collect(preg_split('/\r\n|\r|\n/', (string) ($data['achievements'] ?? '')))
            ->map(fn (?string $item): string => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        $experiences = collect($data['work_experiences'] ?? [])
            ->map(function (array $experience): array {
                return [
                    'job_title' => $experience['job_title'],
                    'company' => $experience['company'],
                    'location' => $experience['location'] ?? '',
                    'period' => date('M Y', strtotime($experience['start_date'])).' - '.(($experience['is_current'] ?? false) ? 'Sekarang' : ($experience['end_date'] ? date('M Y', strtotime($experience['end_date'])) : 'Sekarang')),
                    'description_points' => collect(preg_split('/\r\n|\r|\n/', (string) $experience['description']))
                        ->map(fn (?string $point): string => trim(Str::start((string) $point, '')))
                        ->filter()
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        $educations = collect($data['educations'] ?? [])
            ->map(function (array $education): array {
                return [
                    'degree' => $education['degree'],
                    'institution' => $education['institution'],
                    'location' => $education['location'] ?? '',
                    'period' => $education['start_year'].' - '.($education['end_year'] ?: 'Sekarang'),
                    'description' => $education['description'] ?? '',
                ];
            })
            ->values()
            ->all();

        return array_merge($data, [
            'skills_list' => $skills,
            'languages_list' => $languages,
            'certifications_list' => $certifications,
            'achievements_list' => $achievements,
            'work_experiences_prepared' => $experiences,
            'educations_prepared' => $educations,
        ]);
    }
}
