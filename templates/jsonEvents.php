<?php
if(false){
  $event = new \Entities\Event();
  $routing = new \Laget\Routing\ModxRouting();
}
$JSON = array();
foreach ($events as $event){
  $JSON[] = array(
    'id'    => $event->getId(),
    'title' => $event->getTitle(),
    'start' => $event->getStart()->getTimestamp(),
    'end'   => $event->getEnd()->getTimestamp(),
    'url'   => $routing->showEvent($event),
    'allDay'=> false,
    'className'=> 'kalender_klasse_'.$event->getType()->getId(),
  );
}
echo json_encode($JSON);