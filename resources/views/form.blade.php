<html>
    <head>
        <title></title>
        <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://unpkg.com/chota@latest">
        <style>
            #main {
                max-width:1200px;
                margin:auto;
                margin-top:120px;
            }
        </style>
         <style>
    body.dark {
      --bg-color: #000;
      --bg-secondary-color: #131316;
      --font-color: #f5f5f5;
      --color-grey: #ccc;
      --color-darkGrey: #777;
    }
  </style>
  <script>
   $(document).ready(function(){
      if (window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.body.classList.add('dark');
    }
   });
  </script>
    </head>
    <body>
      <nav class="nav">
        <div class="nav-left">
          <a class="brand" href="#">NetControl</a>
          <div class="tabs">
            <a href='#'>Devices</a>
            <a jref="#" class="active">Parameter</a>
          </div>
        </div>
        <div class="nav-right">
          <a class="button outline">Button</a>
        </div>
      </nav>
    <div class="row" id="main">
<div class="col-12 col-6-md col-4-lg">
@foreach ($errors->all() as $error)
            <li class="text-error">{{$error}}</li>
  @endforeach
  @isset($success)
            <li class="text-primary">{{$success}}</li>
  @endisset
  <div class="card is-center">
  {{ Form::open(array('url' => 'add/device','method'=>'post')) }}
    {{Form::label('name','Device name:')}}
    {{Form::text('name')}} 
    {{Form::label('ip','IP:')}}
    {{Form::text('ip')}} 
    {{Form::label('community','Community string')}}
    {{Form::text('community')}}  <br>
    @foreach ($params as $param)
          <input type="checkbox" name="params[]" value="{{$param->id}}">
          <label for="{{$param->param_name}}">{{$param->param_name}}</label> <br>
    @endforeach <br>
    {{Form::submit('add device')}}  
    {{Form::reset('reset form')}}
    {{ Form::close() }}
   </div>
   <hr>
   <div class="card is-center">
   {{Form::open(array('url'=>'/add/param','method'=>'post'))}}
   {{Form::label("name","Parameter Name:")}}
   {{Form::hidden("value_type","SNMP")}}
   {{Form::text("param_value")}}
   {{Form::label("snmp","SNMP oid")}}
   {{Form::text("pram_name")}}
   <br>
   {{Form::submit("add param")}}
   {{Form::reset("reset form")}}
   {{Form::close()}}
   </div>
</div>
<div class="col-6 col-6-md col-6-lg">
    @isset($devices)
            @foreach($devices as $device)
              <a  href="{{URL('delete_device')}}/{{$device->id}}">
                <span class="tag is-small" style="color:;padding:4px;font-size:11px;margin-right:10px;" id="delete">x</span>
              </a>
              <spam class="text-primary">{{$device->nickname}}: {{$device->ip}} </span>
              @php
                $params = App\Http\Controllers\FormController::get_params($device->id);
                foreach($params as $param)
                {
                  echo "<span class='tag is-small'>".$param->param_name."</span>";
                }
              @endphp <br>
            @endforeach
    @endisset
</div>
</div>
</div>
    </body>
</html>