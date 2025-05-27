<?php

namespace XelentAbrar\HospitalExpense\Http\Controllers\Expenses;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\UserRole;
use XelentAbrar\HospitalExpense\Models\Expenses\Expense;
use Illuminate\Support\Facades\DB;
use XelentAbrar\HospitalExpense\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalExpense\Services\VoucherAuditService;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use XelentAbrar\HospitalExpense\Http\Requests\Expenses\ExpenseRequest;


class ExpenseController extends Controller
{
    public function __construct()
    {
        // $this->middleware('checkPermission:expenses.index')->only('index');
        // $this->middleware('checkPermission:expenses.create')->only('create', 'store');
        // $this->middleware('checkPermission:expenses.show')->only('show');
        // $this->middleware('checkPermission:expenses.edit')->only('edit', 'update');
        // $this->middleware('checkPermission:expenses.destroy')->only('destroy');
    }
    public function index()
    {
        $expenses = Expense::with(['creator:id,name'])->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $todayDate = Carbon::now()->toDateString();
        $latestExpense = Expense::latest()->first();
        if ($latestExpense && $latestExpense->date == $todayDate) {
            $nextReceiptNo = (string) ($latestExpense->receipt_no + 1);
        } else {
            $nextReceiptNo = '1';
        }
        if(file_exists(base_path('config/accounts.php'))) {
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('Expenses/Create' ,[
            'receipt_no' =>  $nextReceiptNo,
            'date' => $todayDate,
            'coa' => $coa ?? null
        ]);
    }
    private function generateReceiptNo()
    {
        return 'REC-' . time();
    }
    
    public function store(ExpenseRequest $request)
    {
        DB::beginTransaction();

        try {
            $lastSerialNo = Expense::max(DB::raw('CAST(serial_no AS UNSIGNED)'));
            $newSerialNo = intval($lastSerialNo) ? intval($lastSerialNo) + 1 : 1;
            $userId = Auth::id();
            $formData = $request->only((new Expense())->getFillable());
            $formData['serial_no'] = $newSerialNo;
            $formData['expense_time'] = Carbon::now()->format('H:i');
            $formData['created_by'] = $userId;
            $expense = Expense::create($formData);
            $voucherAudit = new VoucherAuditService();
            $voucherAudit->storeVoucher('expense', $expense, 'store');

            DB::commit();

            return redirect()->route('expenses.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Error occurred while storing expense');
        }
    }


    public function edit(Expense $expense)
    {
        if(file_exists(base_path('config/accounts.php'))) {
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('HRMS/Expenses/Create', [
            'expenses' => $expense,
            'coa' => $coa ?? null,
        ]);
    }


    public function update(ExpenseRequest $request, Expense $expense)
    {
        DB::beginTransaction();

        $userId = Auth::id();
        $formData = $request->only($expense->getFillable());
        $formData['expense_time'] = Carbon::now()->format('H:i');
        $formData['updated_by'] = $userId;
        $voucher_audit = new VoucherAuditService();
        $voucher_audit = $voucher_audit->storeVoucher('expense', $formData, 'update', $expense->id);
        if(!$voucher_audit){
            return redirect()->route('expenses.index')->with('message', 'Expense Already Sent to Accounts.');
        }
        $expense->update($formData);

        DB::commit();

        return redirect()->route('expenses.index')->with('message', 'Expense updated successfully.');
    }


    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
