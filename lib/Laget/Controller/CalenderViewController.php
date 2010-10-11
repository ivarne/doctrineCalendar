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
    $eventRepository = new EventRepository($this->em,$this->em->getClassMetadata('Entities\Event'));
    $this->events = $eventRepository->getNextEvents(10, $this->getUser()->hasPermission('se upubliserte'), new \DateTime());
    return $this->render('kalenderListe');
  }
  public function executeIcal(){
    $eventRepository = new EventRepository($this->em,$this->em->getClassMetadata('Entities\Event'));
    $this->events = $eventRepository->getNextEvents(60,false,new \DateTime('-1 week'));

    return $this->render('icalEvent');
  }
  public function executeListFrontpage(){
    $eventRepository = $this->em->getRepository('\Entities\Event');
    $this->events = $eventRepository->getNextEvents(6);
    return $this->render('listFrontpage');
  }
  public function executeListJson(){
    $eventRepository = $this->em->getRepository('\Entities\Event');
    $datetimestart = new \DateTime();
    $datetimeend = new \Datetime();
    if(isset($_GET['upub'])&& $_GET['upub'] == 'true' &&$this->getUser()->hasPermission('se upubliserte')){
      $onlyPublic = false;
    }else{
      $onlyPublic = true;
    }
    $this->events = $eventRepository->getEventsBetween($datetimestart->setTimestamp($_GET['start']),$datetimeend->setTimestamp($_GET['end']),$onlyPublic);
    return $this->render('jsonEvents');
  }
  public function executeShowEvent(){
    $onlyPublic = !$this->getUser()->hasPermission('se upubliserte');
    $event = $this->getEntityManager()->getRepository('\Entities\Event')->find($_GET['event'],$onlyPublic);
    if(!isset($event)){
      throw new \Exception('Det finnes ingen hendelse med id = '.htmlspecialchars($_GET['event'], \ENT_QUOTES , 'UTF-8'));
    }
    $this->event = $event;
    return $this->render('visHendelse');
  }
  public function executeListSpeakers(){
    $limit = 10;
    $page = 0;
    if(isset($_GET['limit']) && is_integer($_GET['limit'])){
      $limit = $_GET['limit'];
    }if(isset($_GET['page']) && is_integer($_GET['page'])){
      $page = $_GET['page'];
    }
    $this->speakers = $this->getEntityManager()
            ->getRepository('\Entities\Speaker')
            ->getMostActiveSpeakers($limit,$page*$limit);
  return $this->render('listSpeakers');
  }
}