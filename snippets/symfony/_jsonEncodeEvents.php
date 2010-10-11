<?php
if(false){
  $event = new Events();
}


$JSON = array();
foreach($events as $event){
  $json = array(
    'id'    => $event->getId(ESC_RAW),
    'title' => $event->getTitle(ESC_RAW),
    'start' => $event->getStart(ESC_RAW),
    'end'   => $event->getSlutt(ESC_RAW),
    'url'   => $event->getUrl(ESC_RAW),
    'allDay'=> false,
    'className'=> 'kalender_klasse_'.$event->getEventType(ESC_RAW),
  );
  $JSON[] = $json;
}

echo json_encode($JSON);