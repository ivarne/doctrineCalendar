<?php
print 'start';


// try to get outside temperature from yr.no to find out when you need to start
// heating to be sure the room is warm when the event starts.
$xml = file_get_contents("http://www.yr.no/place/Norway/S%C3%B8r-Tr%C3%B8ndelag/Trondheim/Berg_presteg%C3%A5rd/varsel.xml");
$simple_xml = simplexml_load_string($xml);
$temp = (int)$simple_xml->observations[0]->weatherstation[0]->temperature[0]["value"];
var_dump( $temp );
exit();

$con = mysql_connect('job-web', "calendar_heat", "4b3z6G8pywPHHMuj");
$sql = "SELECT COUNT(`startTS`) as val FROM `doctrineKalender`.`eventkalender` WHERE `endTS` > NOW() AND `startTS` < NOW() + INTERVAL 20 HOUR";
print $sql;
$res = mysql_query($sql, $con);
var_dump($res);
$res = mysql_fetch_row($res);
var_dump($res);
if($res[0]){// An event is ongoing or comming soon
    exec("tdtool --on 1"); // turn light on
    exec("tdtool --off 2"); // turn the setback temperature off
}else{
    exec("tdtool --off 1"); // turn light off
    exec("tdtool --on  2"); // turn the setback temperature on
}