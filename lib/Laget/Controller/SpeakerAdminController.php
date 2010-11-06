<?php
namespace Laget\Controller;
use Entities\Speaker;
/**
 * Description of SpeakerAdminController
 *
 * @author ivarne
 */
class SpeakerAdminController extends BaseController {

  static $tic = 'tic';

  public function executeMain() {
    $this->speaker = new Speaker();
    return $this->render('speakerAdmin');
  }
  public function executeEdit() {
    $this->speaker = $this->getSpeakerRepository()->find((int)$_GET['speakerId'],false);
    return $this->render('speakerAdmin');
  }
  public function executeSave() {
    if(!isset($_POST['speakerId'])) {
      $speaker = new Speaker();
      $this->getEntityManager()->persist($event);
    }elseif(!empty($_POST['speakerId'])) {
      $speaker = $this->getSpeakerRepository()->find((int)$_POST['speakerId']);
      if($speaker->getVersion() != $_POST['version']) {
        echo '<span class="error">';
        echo __('Beklager, noen har redigert denne taleren i mellomtiden så du må gjøre dine endringer på nytt');
        echo '</span>';
        $_GET['speakerId'] = (int)$_POST['speakerId'];
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
      if($this->routing instanceof \Laget\Routing\ModxRouting){
        header('Location: '.$this->routing->showSpeaker($speaker));
        die();
      }
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
            ->setAbout($_POST['about_en'], 'en')
            ->setTelephone($_POST['tlf'])
            ->setEmail($_POST['email']);
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