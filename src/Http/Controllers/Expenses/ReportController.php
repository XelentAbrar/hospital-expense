<?php

namespace XelentAbrar\HospitalExpense\Http\Controllers\Expenses;

use Inertia\Inertia;
use XelentAbrar\HospitalExpense\Models\Expenses\Expense;
use XelentAbrar\HospitalExpense\Http\Controllers\Controller;
use DateTime;

class ReportController extends Controller
{
    
    public function expenseReport()
    {
        $reports = new Expense();
        $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        $to_date = date('Y-m-d H:i', strtotime('today 23:59'));

        if (isset($_GET['from_date']) && $_GET['from_date'] != '') {
            $from_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['from_date']);
            if (!$from_date) {
                throw new \Exception('Invalid from_date format. Expected d-m-Y H:i.');
            }
            $from_date = $from_date->format('Y-m-d H:i');
        } else {
            $from_date = date('Y-m-d H:i', strtotime('today 00:00'));
        }

        if (isset($_GET['to_date']) && $_GET['to_date'] != '') {
            $to_date = DateTime::createFromFormat('d-m-Y H:i', $_GET['to_date']);
            if (!$to_date) {
                throw new \Exception('Invalid to_date format. Expected d-m-Y H:i.');
            }
            $to_date = $to_date->format('Y-m-d H:i');
        } else {
            $to_date = date('Y-m-d H:i', strtotime('today 23:59'));
        }
        $from_date_only = date('Y-m-d', strtotime($from_date));
        $from_time_only = date('H:i:s', strtotime($from_date));
        $to_date_only = date('Y-m-d', strtotime($to_date));
        $to_time_only = date('H:i:s', strtotime($to_date));

        // $reports = $reports->whereBetween('date', [$from_date, date('Y-m-d', strtotime($to_date . ' +1 day'))]);
        $reports = $reports->where(function ($query) use ($from_date_only, $to_date_only, $from_time_only, $to_time_only) {
            $query->whereBetween('date', [$from_date_only, $to_date_only])
                  ->whereBetween('expense_time', [$from_time_only, '23:59:59']);
        })->orWhere(function ($query) use ($to_date_only, $to_time_only) {
            $query->where('date', $to_date_only)
                  ->where('expense_time', '<=', $to_time_only);
        });
        $reports = $reports->get();

        return Inertia::render('Expenses/Report', [
            'reports' => $reports,
            'from_date' => date('d-m-Y H:i', strtotime($from_date)),
            'to_date' => date('d-m-Y H:i', strtotime($to_date)),
        ]);
    }

}
