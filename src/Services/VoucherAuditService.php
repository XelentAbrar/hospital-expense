<?php

namespace XelentAbrar\HospitalExpense\Services;

use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAudit;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalAccounts\Models\Accounts\VoucherAuditDetail;
use XelentAbrar\HospitalAccounts\Models\AccountSetting;
use App\Models\HRMS\UserCashSummary;

class VoucherAuditService
{
    function storeVoucher($type, $data, $status, $parent_id = null, $reverse = null) {
        if(file_exists(base_path('config/accounts.php'))) {
            if($status == 'store'){
                return $this->storeExpense($data, $reverse);
            }
            else if(VoucherAudit::where('type', 'expense')->where('parent_id', $parent_id)->exists()){
                $id = VoucherAudit::where('type', 'expense')->where('parent_id', $parent_id)->value('id');
                VoucherAuditDetail::where('voucher_audit_id', $id)->delete();
                VoucherAudit::whereId($id)->delete();
                $data['id'] = $parent_id;
                return $this->storeExpense($data);
            }
            else{
                return 0;
            }
        }
    }

    function storeExpense($data, $type = null) {
        $advCoaValue = AccountSetting::where('key', 'Advance')->value('value');
        $cashCoaValue = AccountSetting::where('key', 'cash_in_hand')->value('value');
        $voucher = VoucherAudit::create([
            'voucher_type_id' => '2',
            'type' => 'expense',
            'parent_id' => $data['id'],
            'voucher_date' => $data['date'],
            // 'narration' => $data['description'],
            'narration' =>'Slip No : ' . $data['id'] . ' - ' . $data['description'],
            'created_by' => \Auth::id()
        ]);
        VoucherAuditDetail::create([
            'voucher_audit_id' => $voucher->id,
            'acc_code' => $data['coa_id'],
            'dr' => $type == 'reverse' ? 0 : $data['amount'],
            'cr' => $type == 'reverse' ? $data['amount'] : 0,
            'transaction_type' => 'expense',
            'transaction_no' => $data['id'],
            'remarks' => $data['description']
        ]);
        VoucherAuditDetail::create([
            'voucher_audit_id' => $voucher->id,
            'acc_code' => ChartOfAccount::where('acc_code', $cashCoaValue)->value('id'),
            // 'acc_code' => ChartOfAccount::where('acc_code', '101051001')->value('id'),
            'dr' => $type == 'reverse' ? $data['amount'] : 0,
            'cr' => $type == 'reverse' ? 0 : $data['amount'],
            'transaction_type' => 'expense',
            'transaction_no' => $data['id'],
            'remarks' => $data['description']
        ]);
        $userId = $data['created_by'] ?? \Auth::id();
        $userCashSummary = UserCashSummary::where('user_id', $userId)
                                          ->latest()
                                          ->first();

        if (!$userCashSummary || $userCashSummary->status == 'complete') {
            UserCashSummary::create([
                'user_id' => $userId,
                'receipt_count' => 1,
                'total_amount' => -$data['amount'],
                'status' => 'pending',
                'activity_time' => $data['expense_date'] . ' ' . ($data['expense_time'] ?? now()->format('H:i:s')),
            ]);
        } else {
            $userCashSummary->increment('receipt_count', 1);
            $userCashSummary->decrement('total_amount', $data['amount']);
            $userCashSummary->update([
                'activity_time' => $data['expense_date'] . ' ' . ($data['expense_time'] ?? now()->format('H:i:s'))
            ]);
        }
        return 1;
    }
}
