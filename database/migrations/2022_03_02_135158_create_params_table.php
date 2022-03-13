<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;

class CreateParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('params', function (Blueprint $table) {
            $table->id();
            $table->string("param_name");
            $table->string("value_type");
            $table->string('param_value')->nullable(); // snmp oid or some other param
            $table->timestamps();
        });

        Artisan::call('db:seed', [

            '--class' => 'DeviceParamSeeder',
    
            //'--force' => true // <--- add this line
    
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('params');
    }
}
