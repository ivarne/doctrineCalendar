<?php
namespace Laget\Controller;

use Entities;
use Entities\Repositories\EventRepository;
/* 
 * Denne fila inneholder alle actions som kan utføres av kalenderen
 * og extender kalender_base som inneholder diverse utilities og en
 * egnet constructor
 *
 * alle funksjoner skal starte med execute fordi det er navnet funksjonene kalles ved
 * når man kjøre execute($action) på base kalender
*/

class CalenderViewController extends BaseController {
  /**
   * returnerer ingen ting, se på det som eksempelkode
   */
  public function executeJsonDataDump(){
    $events = $this->getEventRepository()->findAll();
    $json = array();
    foreach($events as $event){
      $json[$event->getId()]=array(
        'name_no'=>$event->getName('no'),
        'name_en'=>$event->getName('en'),
        'short_no'=>$event->getShort('no'),
        'short_en'=>$event->getShort('en'),
        'info_no'=>$event->getInfo('no'),
        'info_en'=>$event->getInfo('en'),
      );
    }
  }
  public function executeMonthView(){
    return $this->render('monthView');
  }
  public function executeSimpleMonthView(){
    $year = (int)$_GET['year'];
    $month = (int)$_GET['month'];
    if($year<2000 ||$year > 2200 || $month<1 || $month>12){
      //Set default year mont today
      $year = date('Y');
      $month = date('m');
    }
    if(isset($_GET['showNotPublic'])){
      $onlyPublic = true;
    }else{
      $onlyPublic = false;
    }
    $this->events = $this->getEventRepository()->getEventsBetween(new \DateTime($year.'-'.$month.'-01'), new \DateTime($year.'-'.(($month+1)%12).'-01'),$upub);
    $this->date = new \DateTime($year.'-'.$month.'-01');
    $this->onlyPulic = $onlyPublic;
    $this->render('kalenderListe');
  }
  public function executeList() {
    $this->events = $this->getEventRepository()->getNextEvents(10, $this->getUser()->hasPermission('se upubliserte'), new \DateTime());
    return $this->render('kalenderListe');
  }
  public function executeIcal(){
    $this->events = $this->getEventRepository()->getNextEvents(60,false,new \DateTime('-1 week'));

    return $this->render('icalEvent');
  }
  public function executeListFrontpage(){
    $this->events = $this->getEventRepository()->getNextEvents(5);
    if($this->getUser()->isLoggedIn()){
      $this->userResponsibilities = $this->getEventRepository()->getUserResponsibleEvents($this->getUser()->getDoctrineUser());
    }
    return $this->render('listFrontpage');
  }
  public function executeListFacebook(){
    $this->events = $this->getEventRepository()->getNextEvents(10);
    return $this->render('facebookEvents');
  }
  public function executeListJson(){
    $datetimestart = new \DateTime();
    $datetimeend = new \Datetime();
    $onlyPublic = true;
    if(isset($_GET['upub'])&& $_GET['upub'] == 'true'){
//      if($this->getUser()->isLoggedIn()){
        $onlyPublic = false;
//      }else{
//        return json_encode(array('error'=>'User Not Logged Inn'));
//      }
    }
    $events = $this->getEventRepository()->getEventsBetween($datetimestart->setTimestamp($_GET['start']),$datetimeend->setTimestamp($_GET['end']),$onlyPublic);
    $JSON = array();
    foreach ($events as $event){
      $JSON[] = array(
        'id'    => $event->getId(),
        'title' => $event->getTitle(),
        'start' => $event->getStart()->getTimestamp(),
        'end'   => $event->getEnd()->getTimestamp(),
        'url'   => $this->routing->showEvent($event),
        'info'  => $event->getShort(),
        'allDay'=> false,
        'className'=> 'kalender_klasse_'.($event->isPublic()?'pub':'upub'),
      );
    }
    return json_encode($JSON);
  }
  public function executeShowEvent(){
    $onlyPublic = !$this->getUser()->isLoggedIn();
    $event = $this->getEventRepository()->findPublic((int)$_GET['event'],$onlyPublic);
    if($event == NULL){
      return __('Det finnes ingen hendelse med id: %id% eller du har ikke tilgang til å vise den.<br>Prøv å <a href="/no/medlemssider/logginn">loge inn</a>',array('%id%'=>(int)$_GET['event']));
    }
    $this->concurentEvents = $this->getEventRepository()->getConcurrentEvents($event);
    $this->event = $event;
    $this->registrerFacebookOpenGrapTags(array(
      'og:title'=>$event->getTitle(),
//      'og:type' =>'article',
      'og:image'=>'http://www.laget.net/picturegenerator.php?text='.urlencode($event->getTitle()),
      'og:url'=>'http://www.laget.net'.$this->routing->showEvent($event),
      'og:description'=>$event->getShort().($event->hasSpeaker()?' '.__('Taler').': '.$event->getSpeaker()->getName():'').__(' Klokka ').$event->getStart('%R %A %e. %b'),
    ));
    return $this->render('visHendelse');
  }
  public function executeListResponsibilitiesUser(){
    if(!$this->getUser()->isLoggedIn()){
      return '';
    }
    $this->events = $this->getEventRepository()->getUserResponsibleEvents($this->getUser()->getDoctrineUser());
    return $this->render('listFrontpage');
  }
  /**
   *
   * @return \Entities\Repositories\EventRepository
   */
  private function getEventRepository(){
    return $this->getEntityManager()->getRepository('\Entities\Event');
  }
}