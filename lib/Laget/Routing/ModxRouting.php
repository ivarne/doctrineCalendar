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
  );
  private $modx;
  private $lang;
  public function  __construct($lang) {
    global $modx;
    $this->modx = $modx;
    $this->lang = $lang;
  }
  public function showEvent(\Entities\Event $event){
    return $this->modx->makeUrl($this->docid['show'][$this->lang], '', '?event='.$event->getId());
  }
  public function monthView(\DateTime $date){
    return $this->modx->makeUrl($this->docid['monthView'][$this->lang], '', '#'.$date->format('Y-m-d').'?month');
  }
  public function editEvent(\Entities\Event $event){
    return $this->modx->makeUrl($this->docid['edit'][$this->lang],'', '?action=edit&event='.$event->getId());
  }
  public function deleteEvent(\Entities\Event $event){
    return $this->modx->makeUrl($this->docid['edit'][$this->lang],'', '?action=delete&event='.$event->getId());
  }
  public function saveEvent(){
    return $this->modx->makeUrl($this->docid['edit'][$this->lang],'', '?action=save');
  }
  public function publishEvent(\Entities\Event $event){
    return $this->modx->makeUrl($this->docid['edit'][$this->lang],'', '?action=publish&event='.$event->getId());
  }
  public function searchForEvent(){
    return '';
  }
  public function newEvent(){
    return $this->modx->makeUrl($this->docid['edit'][$this->lang],'', '');
  }
  public function JSONevents(){
    return $this->modx->makeUrl($this->docid['eventListJSON'][$this->lang],'','');
  }
  public function listSpeakers(){
    return $this->modx->makeUrl($this->docid['listSpeakers'][$this->lang],'','');
  }
  public function showSpeaker(\Entities\Speaker $speaker){
    return $this->modx->makeUrl($this->docid['showSpeaker'][$this->lang],'','?speakerId='.$speaker->getId());
  }
  public function editSpeaker(\Entities\Speaker $speaker){
     return $this->modx->makeUrl($this->docid['editSpeaker'][$this->lang],'','?action=edit&amp;speakerId='.$speaker->getId());
  }
  public function saveSpeaker(\Entities\Speaker $speaker){
    return $this->modx->makeUrl($this->docid['editSpeaker'][$this->lang],'','?action=save&amp;speakerId='.$speaker->getId());
  }
  public function login(){
    return $this->modx->makeUrl($this->docid['login'][$this->lang],'','');
  }
  public function logout(){
    return $this->modx->makeUrl($this->docid['logout'][$this->lang],'','');
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