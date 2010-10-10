<?php
namespace Laget\Routing;

class DummyRouting implements RoutingInterface {
  public function  __construct() {
    
  }
  public function showEvent(\Entities\Event $event){
    return '?action=CalenderView:visHendelse&amp;event='.$event->getId();
  }
  public function editEvent(\Entities\Event $event){
    return '?action=CalenderAdmin:edit&amp;event='.$event->getId();
  }
  public function saveEvent(){
    if($event->getId()!=NULL){
      return '?action=CalenderAdmin:save&amp;event='.$event->getId();
    }
    return '?action=CalenderAdmin:save';
  }
  public function newEvent(){
    return '?action=CalenderAdmin:newForm';
  }
  public function deleteEvent(\Entities\Event $event){
   return 'http://laget.net';
  }
  public function monthView(\DateTime $date){
    return '?action=CalenderView:list';
  }
  public function publishEvent(\Entities\Event $event){
    return '';
  }
  public function searchForEvent(){
    return '';
  }
  public function JSONevents(){
    return '';
  }
}