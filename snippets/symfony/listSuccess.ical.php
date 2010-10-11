<?php
/*
 *
 * @var Events $event
 */
if(false){
  //fiks autocomplete
  $event = new Events();
}
$v = new vcalendar();
  // create a new calendar instance
$v->setConfig( 'laget.net', 'icaldomain.com' );
  // set Your unique id

$v->setProperty( 'method', 'PUBLISH' );
  // required of some calendar software
$v->setProperty( "x-wr-calname", isset($calendarName) ? $calendarName : 'laget.net' );
  // required of some calendar software
$v->setProperty( "X-WR-CALDESC", "Kalender fra laget.net" );
  // required of some calendar software
$v->setProperty( "X-WR-TIMEZONE", "Europe/Oslo" );
  // required of some calendar software
foreach($events as $event){
  $vevent = new vevent();
    // create an event calendar component
  $vevent->setProperty( 'dtstart', $event->getDateTimeObject('start')->format('Ymd\THis') );
  $vevent->setProperty( 'dtend', $event->getDateTimeObject('start')->format('Ymd\THis'));
  //$vevent->setProperty( 'LOCATION', 'Central Placa' );
    // property name - case independent
  $vevent->setProperty( 'summary', $event->getShort() );
  $vevent->setProperty( 'description', $event->getInfo() );
  //$vevent->setProperty( "organizer" , $event->getAnsvarlig );
  $vevent->setProperty('uid',$event->getId().'@laget.net');

  $v->setComponent ( $vevent );
  // add event to calendar
}
  // all calendar components are described in rfc2445
  // a complete iCalcreator function list (ex. setProperty) in iCalcreator manual

echo $v->createCalendar();
