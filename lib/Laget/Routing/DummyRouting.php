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
    return 'test.php';
    return '?action=CalenderAdmin:save';
  }
  public function newEvent(){
    return '?action=CalenderAdmin:main';
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
  public function showSpeaker(\Entities\Speaker $speaker){
    return '?action=SpeakerView:Show&amp;speakerId='.$speaker->getId();
  }
  public function listSpeakers(){
    return '?action=SpeakerView:list';
  }
  public function editSpeaker(\Entities\Speaker $speaker){
    
  }
  public function saveSpeaker(\Entities\Speaker $speaker){
    
  }
  public function login(){

  }
  public function logout(){

  }
}