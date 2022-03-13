<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
class FormController extends Controller
{
    //
    public function delete_device($device_id)
    {
        DB::table('devices')->where('id',$device_id)->delete();
        return redirect()->back();
    }
    static public function get_params($device_id)
    {
        $param_ids = (DB::table("devices")
                        ->select("device_params")
                        ->where("id",$device_id)
                        ->first());
        $params = [];
        foreach(unserialize($param_ids->device_params) as $param_id)
        {
            $params[] = DB::table("params")->select("param_name")->where("id",$param_id)->first();
        }
        return $params;
    }
    public function index()
    {
        $devices = DB::table('devices')->get();
        $params  = DB::table("params")->get();
        return view("form")->with(['devices'=>$devices,'params'=>$params]);
    }
    public function add_param(Request $rq)
    {
       $validated  = $rq->validate([
           'param_name'=>'required|min:5|max:255|unique:params,param_name',
           'param_value'=>'required|min:5'
       ]);
       if($validated)
       {
           $query = DB::table("params")
                ->insert([
                    'param_name'=>$rq->input('param_name'),
                    'value_type'=>$rq->input('value_type'),
                    'param_value'=>$rq->input('param_value')
                ]);
                if($uqery)
                {
                    return redirect('/form');
                }
       }
    }
    public function add_device(Request $rq)
    {
        $validated = $rq->validate([
            'params'=>'array|min:1|required',
            'name'=>'required|max:255|unique:devices,nickname',
            'ip'=>'required|ipv4|unique:devices,ip',
            'community'=>'required'
        ]);
        $query = DB::table("devices")
                ->insert([
                    'nickname'=>$rq->input("name"),
                    'ip'=>$rq->input('ip'),
                    'device_params'=>serialize($rq->input('params')),
                    'type'=>'netcontroller',
                    'serialnumber'=>rand(10000,2090000),
                    'auth'=>$rq->input('community'),
                    'created_at'=>Carbon::now()->timezone('Europe/Sofia')
                ]);
       if($query)
       {
           return redirect('/form')->with('success','Device added');
       }
    }
}
