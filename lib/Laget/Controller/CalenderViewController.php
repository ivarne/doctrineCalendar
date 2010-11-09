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
  public function executeMonthView(){
    return $this->render('monthView');
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
    $this->events = $this->getEventRepository()->getNextEvents(6);
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
      if($this->getUser()->isLoggedIn()){
        $onlyPublic = false;
      }else{
        return json_encode(array('error'=>'User Not Logged Inn'));
      }
    }
    $this->events = $this->getEventRepository()->getEventsBetween($datetimestart->setTimestamp($_GET['start']),$datetimeend->setTimestamp($_GET['end']),$onlyPublic);
    return $this->render('jsonEvents');
  }
  public function executeShowEvent(){
    $onlyPublic = !$this->getUser()->isLoggedIn();
    $event = $this->getEventRepository()->find((int)$_GET['event'],$onlyPublic);
    if($event == NULL){
      return __('Det finnes ingen hendelse med id: %id% eller du har ikke tilgang til å vise den.<br>Prøv å loge inn',array('%id%'=>(int)$_GET['event']));
    }
    $this->concurentEvents = $this->getEventRepository()->getConcurrentEvents($event);
    $this->event = $event;
    $this->registrerFacebookOpenGrapTags(array(
      'og:title'=>$event->getTitle(),
//      'og:type' =>'article',
//      'og:image'=>'http://www.laget.net/assets/images/lagetlogo_s.png'
      'og:url'=>$this->routing->showEvent($event),
      'og:description'=>$event->getShort().($event->hasSpeaker()?' '.__('Taler').': '.$event->getSpeaker()->getName():''),
    ));
    return $this->render('visHendelse');
  }
  /**
   *
   * @return \Entities\Repositories\EventRepository
   */
  private function getEventRepository(){
    return $this->getEntityManager()->getRepository('\Entities\Event');
  }
}