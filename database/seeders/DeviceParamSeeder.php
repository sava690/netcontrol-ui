<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeviceParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("params")->truncate();
        
        $data =[[
                    'param_name'=>'temperature',
                    'value_type'=>'integer',
                    'created_at'=>Carbon::now()
                ],[
                    'param_name'=>'battery',
                    'value_type'=>'integer',
                    'created_at'=>Carbon::now()
                ],[
                    'param_name'=>'ac',
                    'value_type'=>'integer',
                    'created_at'=>Carbon::now()
                ]];
        
        DB::table('params')
                ->insert($data);

        
        DB::raw("REATE EXTENSION IF NOT EXISTS timescaledb CASCADE;");
        DB::raw("SELECT create_hypertable('device_history','time')");
    }
}
