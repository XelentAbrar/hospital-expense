<?php

namespace XelentAbrar\HospitalExpense\Models\Expenses;

use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'amount',
        'payment_method',
        'expense_time',
        'receipt_no',
        'approved_by',
        'attachments',
        'notes',
        'coa_id',
        'serial_no','created_by','updated_at'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function coa(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }
}
