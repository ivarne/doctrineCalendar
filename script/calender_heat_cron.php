<?php
$con = mysql_connect('localhost', "calendar_heat", "4b3z6G8pywPHHMuj");

$sql = "SELECT COUNT(`startTS`) as val FROM `doctrineKalender`.`eventkalender` WHERE `endTS` > NOW() AND `startTS` < NOW() + INTERVAL 20 HOUR";

$res = mysql_query($sql, $con);
//var_dump($res);
$res = mysql_fetch_row($res);
//var_dump($res);
if($res[0]){// An event is ongoing or comming soon
    exec("tdtool --on 1"); // turn light on
    exec("tdtool --off 2"); // turn the setback temperature off
}else{
    exec("tdtool --off 1"); // turn light off
    exec("tdtool --on  2"); // turn the setback temperature on
}