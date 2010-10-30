<?php
namespace Laget\Controller;
use Entities\Speaker;
/**
 * Description of SpeakerAdminController
 *
 * @author ivarne
 */
class TalerController extends BaseController {

  static $tic = 'tic';

  public function executeMain() {
    return 'hei';
    $this->speaker = new Speaker();
    return $this->render('speakerAdmin');
  }
  public function executeEdit() {
    $this->speaker = $this->getSpeakerRepository()->find((int)$_GET['event'],false);
    $this->prepareForForm();
    $this->eventTypeId = $this->event->getType()->getId();
    $this->concurentEvents = $this->getEventRepository()->getConcurrentEvents($this->event);
    return $this->render('speakerAdmin');
  }
  public function executeSave() {
    if(!isset($_POST['speakerID'])) {
      $speaker = new Speaker();
      $this->getEntityManager()->persist($event);
    }elseif(!empty($_POST)) {
      $speaker = $this->getSpeakerRepository()->find((int)$_POST['speakerID']);
      if($speaker->getVersion() != $_POST['version']) {
        echo '<span class="error">';
        echo __('Beklager, noen har redigert denne taleren i mellomtiden så du må gjøre dine endringer på nytt');
        echo '</span>';
        $_GET['speakerID'] = (int)$_POST['speakerID'];
        return $this->executeEdit();
      }
    }else {
      throw new Exception('Beklager, men du kan ikke lagre hendelse uten å poste noe');
    }
    $speaker->setEdited(new \DateTime());

    $this->error = array();
    $speaker = $this->populateSpeakerFromPost($speaker);

    if(empty($this->error) && $speaker->isValid($this->error)) {
      $this->getEntityManager()->flush();
      header('Location: '.$this->routing->showSpeaker($speaker));
      die();
      return "Talerinfo ble lagret ble lagret";
    }
    else {
      $this->getEntityManager()->detach($event);
      $this->speaker = $speaker;
      return $this->render('speakerAdmin');
    }
  }
  private function populateSpeakerFromPost(Speaker $speaker) {
    $speaker
            ->setName($_POST['name'])
            ->setAbout($_POST['about_no'], 'no')
            ->setAbout($_POST['about_en'], 'en');
    return $speaker;
  }

  /**
   * Returnerer SpeakerRepository med type hinting
   * @return \Entities\Repositories\SpeakerRepository
   */
  private function getSpeakerRepository() {
    return $this->getEntityManager()->getRepository('\Entities\Speaker');
  }
}