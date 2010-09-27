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
  private $modx;
  private $lang;
  public function  __construct($lang) {
    global $modx;
    $this->modx = $modx;
    $this->lang = $lang;
  }
  public function showEvent(\Entities\Event $event){
    $docid = array(
      'no' => 245,
      'en' => 234,
    );
    return $this->modx->makeUrl($docid[$this->lang], '', '');
  }
  public function editEvent(\Entities\Event $event){
    $docid = array(
      'no' => 245,
      'en' => 234,
    );
    return $this->modx->makeUrl($docid[$this->lang], '', '');
  }
  public function deleteEvent(\Entities\Event $event){
    $docid = array(
      'no' => 245,
      'en' => 234,
    );
    return $this->modx->makeUrl($docid[$this->lang], '', '');
  }
  public function monthView(\DateTime $date){

  }
  public function saveEvent(\Entities\Event $event){
    if($event instanceof \Entities\Event){
      return '?action=CalenderAdmin:save&amp;event='.$event->getId();
    }
    return '?action=CalenderAdmin:save';
  }
  public function publishEvent(\Entities\Event $event){
    return '';
  }
  public function searchForEvent(){
    return '';
  }
  public function newEvent(){
    return '?action=CalenderAdmin:new';
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