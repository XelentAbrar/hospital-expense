<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(file_exists(base_path('config/hrms.php'))) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->date("date")->nullable();
                $table->string("description")->nullable();
                $table->string("amount")->nullable();
                $table->string("payment_method")->nullable();
                $table->string("receipt_no")->nullable();
                $table->string("approved_by")->nullable();
                $table->longText("attachments")->nullable();
                $table->longText("notes")->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(file_exists(base_path('config/hrms.php'))) {
            Schema::dropIfExists('expenses');
        }
    }
};
