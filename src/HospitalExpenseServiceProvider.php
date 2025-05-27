<?php

namespace XelentAbrar\HospitalExpense;

use Illuminate\Support\ServiceProvider;

class HospitalExpenseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'HospitalExpense');
        $this->loadViewsFrom(__DIR__.'/../resources/js', 'jspages');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/expense.php' => config_path('expense.php'),
        ], 'config');


        $this->publishes([
            __DIR__.'/../resources/js/Pages' => resource_path('js/Pages'),
        ], 'expense-vue');

        // $this->publishes([
        //     __DIR__.'/routes/admin' => resource_path('./../routes/admin'),
        // ], 'expense-routes');
        
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/expense.php', 'expense');
    }
}
