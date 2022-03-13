<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class logDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:devices';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logging different netcontrol params';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function get_temperature($ip,$comm,$device_id,$param_id)
    {
            try {
                $message = snmp2_get($ip,$comm,".1.3.6.1.4.1.19865.1.2.3.1.0");
            } catch (\Exception $e)
            {
                //$this->error($e->getMessage());
            }
            if(isset($message)) {
            $value = str_replace("INTEGER:","",$message);
            $voltage = 3300*($value/1023); $voltage = round($voltage,2);
            $temperature =  ($voltage - 500)/10.0; $temperature = round($temperature,2);
              $query =  DB::table("device_history")
                    ->insert([
                        'device_id'=>$device_id,
                        'param_id'=>$param_id,
                        'value_str'=>$temperature,
                        'time'=>Carbon::now()->timezone('Europe/Sofia')
                    ]);
                    if($query)
                    {
                        DB::table("devices")
                                ->where("id",$device_id)
                                ->update([
                                    'updated_at'=>Carbon::now()->timezone('Europe/Sofia')
                                ]);
                    }
                }
    }
    public function get_current($ip,$comm,$device_id,$param_id)
    {
            try {
                $message = snmp2_get($ip,$comm,".1.3.6.1.4.1.19865.1.2.3.4.0");
            } catch (\Exception $e)
            {
                //
            }
            if(isset($message)) {
                $value = str_replace("INTEGER:","",$message);
                $millivolt = (1000*(3.3*$value/1023))/100;
                $current =  round($millivolt/0.90909090,2);
                $query = DB::table("device_history")
                        ->insert([
                            'device_id'=>$device_id,
                            'param_id'=>$param_id,
                            'value_str'=>$current,
                            'time'=>Carbon::now()->timezone('Europe/Sofia')
                        ]);
                        if($query)
                        {
                            DB::table("devices")
                                    ->where("id",$device_id)
                                    ->update([
                                        'updated_at'=>Carbon::now()->timezone('Europe/Sofia')
                                    ]);
                        }
            }
    }
    public function get_battery($ip,$comm,$device_id,$param_id)
    {
            try {
                $message = snmp2_get($ip,$comm,".1.3.6.1.4.1.19865.1.2.3.8.0");
            } catch(\Exception $e)
            {
            }
            if(isset($message)) {
                $value = str_replace("INTEGER:","",$message);
                $millivolt = (1000*(3.3*$value/1023))/100;
                $battery =  round($millivolt/0.90909090,2)."";
                $query = DB::table("device_history")
                        ->insert([
                            'device_id'=>$device_id,
                            'param_id'=>$param_id,
                            'value_str'=>$battery,
                            'time'=>Carbon::now()->timezone('Europe/Sofia')
                        ]);
                        if($query)
                        {
                            DB::table("devices")
                                    ->where("id",$device_id)
                                    ->update([
                                        'updated_at'=>Carbon::now()->timezone('Europe/Sofia')
                                    ]);
                        }
            }
    }
    public function log_param($device_ip,$device_id,$comm,$param_id)
    {
        switch ($param_id) {
            case '1': // temperature
                $this->get_temperature($device_ip,$comm,$device_id,$param_id);
                break;
            case '2':
                $this->get_battery($device_ip,$comm,$device_id,$param_id);
                break;
            case '3':
                $this->get_current($device_ip,$comm,$device_id,$param_id);
                break;
            default:
                # code...
                break;
        }
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $devices = DB::table("devices")->get();
        foreach($devices as $device)
        {
            foreach(unserialize($device->device_params) as $param_id)
            {
               $this->log_param($device->ip,$device->id,$device->auth,$param_id);
            }
        }
        return 0;
    }
}
