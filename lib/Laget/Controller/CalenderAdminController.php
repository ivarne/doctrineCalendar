<?php
namespace Laget\Controller;

use Entities\Event;

class CalenderAdminController extends BaseController {

  public function executeMain() {
    $this->event = new Event();
    if(isset($_GET['date'])){
      $this->event->setStart(new \DateTime($_GET['date']));
    }
    $this->prepareForForm();
    $this->numNewResponsibility = 6;
    return $this->render('admin');
  }
  public function executeEdit() {
    $this->event = $this->getEventRepository()->find((int)$_GET['event'],false);
    if($this->event == NULL){
      return __('Det finnes ingen hendelse med id (%id%',array('%id%'=>htmlspecialchars($_GET['event'])));
    }
    $this->prepareForForm();
    $this->eventTypeId = $this->event->getType()->getId();
    if($this->event->hasSpeaker())
      $this->speakerId = $this->event->getSpeaker()->getId();
    $this->concurentEvents = $this->getEventRepository()->getConcurrentEvents($this->event);
    return $this->render('admin');
  }
  public function executePublish(){
    $event = $this->getEventRepository()->find((int)$_GET['event'],false);
    if($event == NULL){
      return __('Det finnes ingen hendelse med id (%id%',array('%id%'=>htmlspecialchars($_GET['event'])));
    }
    $event->setIsPublic(!$event->isPublic());
    $event->setEdited(new \Datetime());
    $this->getEntityManager()->flush();
    header('Location:'.$this->routing->showEvent($event));
    return 'Hendelsen '.$event->getTitle().' ble '.$event->isPublic()?'publiser':'Upublisert';
  }
  public function executeDelete(){
    $event = $this->getEventRepository()->find((int)$_GET['event'],false);
    if($event == NULL){
      return __('Det finnes ingen hendelse med id (%id%)',array('%id%'=>htmlspecialchars($_GET['event'])));
    }
    if(!isset($_POST['bekreft_sletting']) || $_GET['event']!=$_POST['event']){
      $this->event = $event;
      return $this->render('deleteEvent');
    }
    $this->getEntityManager()->remove($event);
    $this->getEntityManager()->flush();
    return 'Hendelsen '.$event->getTitle().' ble slettet (dette kan ikke angres)';
  }
  public function executeSave() {
    if(!isset($_POST['id'])) {
      $event = new Event();
    }elseif(!empty($_POST)) {
      $event = $this->getEventRepository()->find((int)$_POST['id'],false);
      if($event->getVersion() != $_POST['version']) {
        echo '<span class="error">';
        echo __('Beklager, noen har redigert denne hendelsen i mellomtiden så du må gjøre dine endringer på nytt');
        echo '</span>';
        $_GET['event'] = (int)$_POST['id'];
        return $this->executeEdit();
      }
    }else {
      throw new Exception('Beklager, men du kan ikke lagre hendelse uten å poste noe');
    }

    $this->error = array();
    $event = $this->populateEventFromPost($event);

    if(empty($this->error) && $event->isValid($this->error)) {
      if(!isset($_POST['id'])){
        $this->getEntityManager()->persist($event);
      }
      $this->getEntityManager()->flush();
      header('Location: '.$this->routing->showEvent($event));
      die();
      return "hendelsen ble lagret";
    }
    else {
      $this->prepareForForm();
      $this->getEntityManager()->detach($event);
      $this->event = $event;
      $this->eventTypeId = (int)$_POST['event_type'];
      $this->speakerId = (int)$_POST['speakerId'];
      return $this->render('admin');
    }
  }
  private function populateEventFromPost(Event $event) {
    $event
            ->setTitle($_POST['title_no'], 'no')
            ->setTitle($_POST['title_en'], 'en')
            ->setShort($_POST['short_no'], 'no')
            ->setShort($_POST['short_en'], 'en')
            ->setInfo($_POST['info_no'], 'no')
            ->setInfo($_POST['info_en'], 'en')
            ->setInternalInfo($_POST['internal_info'])
            ->setIsPublic($_POST['isPublic']=='on');

    //Referanser
    $type = $this->getEntityManager()->getRepository('\Entities\EventType')->find((int)$_POST['event_type']);
    if(!is_null($type)){
      $event->setType($type);
    }else{
      $this->error[] = 'The type is invalid. There is no eventType('.(int)$_POST['event_type'].')';
    }
    if(is_numeric($_POST['speakerId'])) {
      $event->setSpeaker($this->getEntityManager()->getRepository('\Entities\Speaker')->find( (int)$_POST['speakerId']));
      if($event->getSpeaker() == NULL){
        $this->error[] = 'Det finnes ingen taler med id = '.(int)$_POST['speakerId'];
      }
    }elseif($_POST['newSpeaker']) {
        $speaker = new \Entities\Speaker($_POST['newSpeaker']);
        $this->getEntityManager()->persist($speaker);
        $event->setSpeaker($speaker);
    }

    // Slett ansvarsområder
    if(isset($_POST['Responsibility'])) {
      foreach ($_POST['Responsibility'] as $erId => $onOff) {
        if($onOff == 'on') {
          $er =$this->getEntityManager()->getRepository('\Entities\EventResponsibility')->find((int)$erId);
          if($er->getEvent()->getId() == $event->getId()){
            $this->getEntityManager()->remove($er);
          }else{
            throw new \Exception('Programeringsfeil: Kunne ikke slette EventResponsibility('.$er->getId().')');
          }
        }
      }
    }

    // Legg til nye ansvarsområder
    foreach($_POST['newResponsibility'] as $newResp) {
      if(!(int)$newResp['respId']) {
        continue;
      }
      $resp = $this->em->getRepository('\Entities\Responsibility')->find((int)$newResp['respId']);
      if($resp == NULL){
        throw new \Exception('Noe gikk galt!!');
      }
      $comment = $newResp['comment'];
      if((int)$newResp['userId']) {
        $user = $this->em->getReference('\Entities\User',(int)$newResp['userId']);
      }else{
        $user = $this->em->getRepository('\Entities\User')->search($newResp['comment']);
        if($user) {
          $comment = null;
        }
      }
      $eventResp = new \Entities\EventResponsibility($resp, $user, $comment);
      $this->em->persist($eventResp);
      $event->setResponsibility($eventResp);
    }

    //Datohåndtering
    $start = new \DateTime($_POST['date']);
    $end = new \DateTime($_POST['date']);
    $matchStart = array();
    if(!preg_match('/(?P<hour>\d{1,2})(:?(?P<min>\d+)?)/',$_POST['clock_start'],$matchStart)) {
      $this->error[] = 'Start tid kunne ikke tolkes som et klokkeslett Formatet er timer:minutter';
    }
    $start->setTime($matchStart['hour'], @$matchStart['min'], 0);
    if($_POST['clock_end']) {
      $matchEnd = array();
      if(!preg_match('/(?P<hour>\d{1,2})(:?(?P<min>\d+)?)/',$_POST['clock_end'],$matchEnd)) {
        $this->error[]= 'Slutt tid kunne ikke tolkes som et klokkeslett Formatet er timer:minutter';
      }
      $end->setTime($matchEnd['hour'], @$matchEnd['min'], 0);
    }else {
      // standard varighet på to timer
      $end->setTime($matchStart['hour'] + 2, @$matchStart['min'], 0);
    }
    if(is_numeric($_POST['days']) && $_POST['days'] >= 0 && $_POST['days'] < 11) {
      $end->add(new \DateInterval('P'.$_POST['days'].'D'));
    }
    $event->setStart($start)
            ->setEnd($end);


    return $event;
  }
  private function prepareForForm() {
    $this->concurentEvents = array();
    $this->eventTypeId = null;
    $this->speakerId = null;
    $this->members = $this->getEntityManager()->getRepository('\Entities\User')->getMembers();
    $this->responsibilities = $this->getEntityManager()->getRepository('\Entities\Responsibility')->findAll();
    $this->eventTypes = $this->getEntityManager()->getRepository('\Entities\EventType')->findAll();
    $this->numNewResponsibility = 4;
    $this->speakers = $this->getEntityManager()->getRepository('\Entities\Speaker')->findAll();
  }
  /**
   * Returnerer EventRepository med type hinting
   * @return \Entities\Repositories\EventRepository
   */
  private function getEventRepository() {
    return $this->getEntityManager()->getRepository('\Entities\Event');
  }
}