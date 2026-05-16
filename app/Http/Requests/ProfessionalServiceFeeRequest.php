<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesCurrencyInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProfessionalServiceFeeRequest extends FormRequest
{
    use NormalizesCurrencyInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'transaction_value' => $this->normalizeCurrencyValue($this->input('transaction_value')),
            'company_revenue' => $this->normalizeCurrencyValue($this->input('company_revenue')),
        ]);
    }

    public function rules(): array
    {
        return [
            'transaction_value' => ['nullable', 'numeric', 'min:0'],
            'company_revenue' => ['nullable', 'numeric', 'min:0'],
            'basis_type' => ['required', 'in:transaction,revenue,highest'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $basisType = $this->string('basis_type')->toString();
            $transactionValue = $this->input('transaction_value');
            $companyRevenue = $this->input('company_revenue');

            if ($basisType === 'transaction' && blank($transactionValue)) {
                $validator->errors()->add('transaction_value', 'Nilai transaksi wajib diisi jika dasar hitung memakai transaksi.');
            }

            if ($basisType === 'revenue' && blank($companyRevenue)) {
                $validator->errors()->add('company_revenue', 'Revenue perusahaan wajib diisi jika dasar hitung memakai revenue.');
            }

            if ($basisType === 'highest' && (blank($transactionValue) || blank($companyRevenue))) {
                $validator->errors()->add('basis_type', 'Isi nilai transaksi dan revenue perusahaan jika ingin membandingkan nilai terbesar.');
            }
        });
    }
}
