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
  public function executeRegistrer(){
    $registration = new \Entities\Registration();
    $event = $this->getEventRepository()->find((int)$_GET['event_id'], false);
    if($event == NULL){
      return __('Det skjedde en feil! Meld deg på manuellt til web[ætt]laget.net');
    }
    $registration
            ->setEvent($event)
            ->setName($_POST['name'])
            ->setEmail($_POST['epost'])
            ->setTlf($_POST['tlf'])
            ->setPublic($_POST['pub'])
            ->setComment($_POST['comment'])
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


    $trans = array(
      '%navn%'=>$registration->getName(),
    );
    if(count($event->getRegistrationTasks())){
      $trans['%gruppe%'] = $registration->getTask()->getName();
      $trans['%gruppe_desc%'] = $registration->getTask()->getDescription();

    }
    if($registration->getUser() && $registration->getUser()->isMember()){
      $trans['%medlem%'] = 'medlem';
      $trans['%pris%'] = $event->getPriceMember();
    }else{
      $trans['%medlem%'] = 'ikke medlem';
      $trans['%pris%'] = $event->getPriceNonMember();
    }
    $message->setBody(strtr($event->getMail(),$trans));
    $mailer->send($message);
    return nl2br($message->getBody());
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
