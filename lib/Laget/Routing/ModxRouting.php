<?php
namespace Laget\Routing;
/**
 * ModxRouting-
 *
 * en klasse for å være et mellomledd mellom vår egen kode og $modx
 * tanken er at det skal være lett å implementere denne på nytt om vi kvitter oss
 * med modx en gang
 */
class ModxRouting implements RoutingInterface{
  /**
   * $modx instace, hovedsakelig for å kunne kalle makeURL();
   * @var DocumentParser
   */
  private $docid = array(
    'edit' => array('no'=>31,'en'=>124),
    'show' => array('no'=>437,'en'=>438),
    'eventListJSON'=> array('no'=>447,'en'=>446),
    'monthView' => array('no'=> 45,'en'=>174),
    'showSpeaker' => array('no'=>449,'en'=>457),
    'editSpeaker' => array('no'=>454,'en'=>454),
    'listSpakers' => array('no'=>448,'en'=>456),
    'login'=>array('no'=> 42, 'en'=> 125),
    'logout'=>array('no'=> 255, 'en'=> 226),
    'registration' => array('no'=>458,'en'=>459),
    'simpleMonth'=> array('no'=>470,'en'=>471),
  );
  private $modx;
  private $lang;
  public function  __construct($lang) {
    global $modx;
    $this->modx = $modx;
    $this->lang = $lang;
  }
  public function showEvent(\Entities\Event $event,$full = null){
    return $this->makeUrl('show', '?event='.$event->getId() , $full);
  }
  public function monthView(\DateTime $date){
    return $this->makeUrl('monthView', '#'.$date->format('Y-m-d').'?month');
  }
  public function editEvent(\Entities\Event $event){
    return $this->makeUrl('edit', '?action=edit&event='.$event->getId());
  }
  public function deleteEvent(\Entities\Event $event){
    return $this->makeUrl('edit', '?action=delete&event='.$event->getId());
  }
  public function saveEvent(){
    return $this->makeUrl('edit', '?action=save');
  }
  public function publishEvent(\Entities\Event $event){
    return $this->makeUrl('edit', '?action=publish&event='.$event->getId());
  }
  public function searchForEvent(){
    return '';
  }
  public function newEvent(){
    return $this->makeUrl('edit');
  }
  public function JSONevents(){
    return $this->makeUrl('eventListJSON');
  }
  public function listSpeakers(){
    return $this->makeUrl('listSpeakers');
  }
  public function showSpeaker(\Entities\Speaker $speaker){
    return $this->makeUrl('showSpeaker','?speakerId='.$speaker->getId());
  }
  public function editSpeaker(\Entities\Speaker $speaker){
     return $this->makeUrl('editSpeaker','?action=edit&amp;speakerId='.$speaker->getId());
  }
  public function saveSpeaker(\Entities\Speaker $speaker){
    return $this->makeUrl('editSpeaker','?action=save&amp;speakerId='.$speaker->getId());
  }
  public function login(){
    return $this->makeUrl('login');
  }
  public function logout(){
    return $this->makeUrl('logout');
  }
  public function saveRegistration(\Entities\Event $event){
    return $this->makeUrl('registration', '?action=registrer&amp;event_id='.$event->getId());
  }
  public function updateRegistrationPaymentInfo(\Entities\Event $event){
    return $this->makeUrl('registration','?action=updatePayments&amp;event_id='.$event->getId());
  }
  public function simpleMonthView($year = NULL,$month = NULL,$upub = false){
    $opts = "";
    if($year && $month){
      $opts .= "year=".(int)$year."&month=".(int)$month;
    }
    if($upub){
        $opts .= '&showNotPublic=1';
    }
    return $this->makeUrl('simpleMonth', $opts);
  }
  private function makeUrl($page,$param = '',$full =false){
    return $this->modx->makeUrl($this->docid[$page][$this->lang],'',$param,$full?'full':'');
  }

  /*
   * GAMLE FUNKSJONER SOM MÅ SKRIVES OM (men som kansje inneholder interessant innfo)
   *
   */
  public function makeUrlForThis(){
    return $this->makeUrlDocID($this->modx->documentIdentifier);
  }
  public function makeUrlForEvent(Event $event) {
    $docid = ($this->lang == 'no' ? 437:438);
    return $this->makeUrlDocID($docid).'?id='.$event->getId();
  }
  public function makeEditUrlForEvent(Event $event ){
    $current = isset($_GET['current'])?'&amp;current='.(int)$_GET['current']:'';
    return $this->makeUrlDocID($this->kalenderedit).'?action=admin&amp;id='.$event->getId().$current;
  }
  public function makePublishUrlForEvent(Event $event){
    $current = isset($_GET['current'])?'&amp;current='.(int)$_GET['current']:'';
    return $this->makeUrlDocID($this->kalenderedit).'?action=admin&amp;id='.$event->getId().$current.'&amp;publisert='.($event->isPublic()?'0':'1');
  }
  public function makeDeleteUrlForEvent(Event $event){
    $current = isset($_GET['current'])?'&amp;current='.(int)$_GET['current']:'';
    return $this->makeUrlDocID($this->kalenderedit).'?action=admin&amp;id='.$event->getId().$current;
  }
}