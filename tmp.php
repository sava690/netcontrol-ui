<?php
function getTemp($ip) {
  $snmp = "snmpget -v1 -c 8668  ".$ip." .1.3.6.1.4.1.19865.1.2.3.1.0";
  $command = exec($snmp);
  $command = explode("INTEGER:",$command);
	$value = trim($command[1]);
  $voltage = 3300*($value/1023); $voltage = round($voltage,2);
  $temperature = ($voltage - 500)/10.0; $temperature = round($temperature,2);
 //  print "voltage: ".$voltage. " temperature: ". $temperature. "\n";
 print $temperature."\n";
}
while(true) {
if(isset( $argv[1]) )
{
  getTemp($argv[1]);
 sleep(1);
}
}
