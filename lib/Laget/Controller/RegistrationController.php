<?php
namespace Laget\Controller;

/**
 * Description of RegistrationController
 *
 * @author ivarne
 */
class RegistrationController extends BaseController {
  public function executeMain(){

  }
  public function executeNotifyForm(){
    $event = $this->getEventRepository()->find((int)$_GET['event_id']);
    
  }
  public function executeSendMail(){
    if(false){
      $reg = new \Entities\Registration();
    }
    $event = $this->getEventRepository()->find((int)$_GET['event_id']);
    $adresses = array();
    foreach($event->getRegistrations() as $reg){
      $adresses[] = array($reg->getEmail() => $reg->getName());
    }
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    
    $mailer = $this->createMailer();
    $message = new \Swift_Message($subject,$body,null,'UTF8');
    $message->setTo($addresses);
    $mailer->batchSend($message, $failedRecipients);
    
  }
  public function executeUpdatePayments(){
    if(false){
      $registration = new \Entities\Registration();
    }
    if(!$this->getUser()->hasPermission('update_registration_paymens')){
      return __('Du har ikke rettighet til å endre betalingsstatus for de påmeldte');
    }
    $event = $this->getEventRepository()->find((int)$_GET['event_id'], false);
    if($event == NULL || !$event->hasPayment()){
      throw new \Exception('Det finnes ingen hendelse med id='.(int)$_GET['event_id']);
    }
    foreach ($event->getRegistrations() as $registration) {
      if($registration->getPayedAmount() != $_POST['payment'][$registration->getId()]
              && isset($_POST['payment'][$registration->getId()])){
        $registration->setPayedAmount($_POST['payment'][$registration->getId()]);
      }
    }
    $this->getEntityManager()->flush();
    return '<a href="'.$this->routing->showEvent($event).'">Tilbake til hendelsen</a>';
  }
  public function executeRegistrer(){
    $registration = new \Entities\Registration();
    $event = $this->getEventRepository()->find((int)$_GET['event_id'], false);
    if($event == NULL){
      return __('Det skjedde en feil! Meld deg på manuellt til web[ætt]laget.net');
    }
    if(!$event->hasOpenRegistration()){
      return __('Det er desverre ingen open registrering for %event%',array('%event%'=>$event->getTitle()));
    }
    $registration
            ->setEvent($event)
            ->setName($_POST['name'])
            ->setEmail($_POST['epost'])
            ->setTlf($_POST['tlf'])
            ->setPublic($_POST['pub'])
            ->setComment($_POST['comment'])
            ->setLang($this->getUser()->getLanguage())
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
    if($this->getUser()->isLoggedIn()){
      $registration->setUser($this->getUser()->getDoctrineUser());
    }else{
      if($user = $this->getUserRepository()->search($registration->getName()));
      elseif($user = $this->getUserRepository()->search($registration->getEmail()));
      elseif($user = $this->getUserRepository()->search($registration->getTlf()));
      if(!is_null($user)){
        $registration->setUser($user);
      }
    }
    if(count($event->getRegistrationTasks())){
      $task = $this->getRegistrationTaskRepository()->find((int)$_POST['task']);
      if($task == null || $task->getEvent()->getId() != $event->getId()){
        return 'Du må gå tilbke og velge en oppgave!';
      }
      if(count($task->getRegistrations()) >= $task->getNumAvailable()){
        $this->error[] = __('Det er desverre fullt på gruppen %grup% så du må gå tilbake og velge en annen gruppe',array('%grup%'=>$task->getName()));
      }
      $registration->setTask($task);
    }
    $this->getEntityManager()->persist($registration);
    $this->getEntityManager()->flush();

    //Send mail
    $mailer = $this->createMailer();
    $message = \Swift_Message::newInstance(__('Påmelding').' '.$event->getTitle());
    $message->setTo($registration->getEmail(), $registration->getName());
    $message->setFrom('ikke-svar@laget.net', 'Laget');
    $message->setReplyTo('ivarne@gmail.com', 'Ivar Nesje');
    
    $message->setBody(strtr($event->getMail(),$this->getTransformations($registration)));
    $mailer->send($message);
    return nl2br($message->getBody());
  }
  private function getTransformations(\Entities\Registration $registration){
    $trans = array(
      '%navn%'=>$registration->getName(),
    );
    if(count($registration->getEvent()->getRegistrationTasks())){
      $trans['%gruppe%'] = $registration->getTask()->getName();
      $trans['%gruppe_desc%'] = $registration->getTask()->getDescription();

    }
    if($registration->getUser() && $registration->getUser()->isMember()){
      $trans['%medlem%'] = 'medlem';
      $trans['%pris%'] = $registration->getEvent()->getPriceMember();
    }else{
      $trans['%medlem%'] = 'ikke medlem';
      $trans['%pris%'] = $registration->getEvent()->getPriceNonMember();
    }
    return $trans;
  }

  /**
   *
   * @return \Entities\Repositories\EventRepository
   */
  private function getEventRepository(){
    return $this->getEntityManager()->getRepository('\Entities\Event');
  }
  /**
   *
   * @return \Entities\Repositories\RegistrationTaskRepository
   */
  private function getRegistrationTaskRepository(){
    return $this->getEntityManager()->getRepository('\Entities\RegistrationTask');
  }
  /**
   *
   * @return \Entities\Repositories\UserRepository
   */
  private function getUserRepository(){
    return $this->getEntityManager()->getRepository('\Entities\User');
  }
}
?>
