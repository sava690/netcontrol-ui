<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AlertController extends Controller
{
    //
    public function index()
    {
        return "ok";
    }

    public function add_annotation()
    {
        /*
        POST /api/annotations HTTP/1.1
        Accept: application/json
        Content-Type: application/json

        {
        "dashboardId":468,
        "panelId":1,
        "time":1507037197339,
        "timeEnd":1507180805056,
        "tags":["tag1","tag2"],
        "text":"Annotation Description"
        }
        */

        $response = Http::withHeaders([
            'Accept'=>'application/json',
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer eyJrIjoiWWlFbFBHNGFSOGRveUZTMnVuTEw3dEQ1SWpzcmxVMTgiLCJuIjoiMTIzIiwiaWQiOjF9'
        ])->post('http://85.187.218.97:3000/api/annotations',[
                "dashboardId"=>13,
                "isRegion"=>false,
                "panelId"=>4,
                "tags"=>["yarrak","zama"],
                "text"=>"Yarrak blah lbah ",
                "time"=> Carbon::now()->getPreciseTimestamp(3),
               "timeEnd"=>0 
        ]);

        return $response;
    }
}
