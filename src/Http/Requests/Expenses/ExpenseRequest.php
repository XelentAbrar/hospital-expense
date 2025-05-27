<?php

namespace XelentAbrar\HospitalExpense\Http\Requests\Expenses;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'date' => 'required|string|max:255',
            'expense_time' => 'nullable',
            'updated_by' => 'nullable',
            'created_by' => 'nullable',
            'description' => 'required|string',
            'amount' => 'required|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'receipt_no' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'attachments' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
            'serial_no' => 'nullable',
        ];
        if(file_exists(base_path('config/accounts.php'))) {
            $rules['coa_id'] =  'required|exists:'. ChartOfAccount::class .',id';
        }

        return $rules;
    }
}
