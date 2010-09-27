<?php
namespace Laget\Controller;

class SpeakerViewController extends BaseController
{
  public function executeList(){
    $this->speakers = $this->getRepository()->getMostActiveSpeakers(10, 0);
    return $this->render('listSpeakers');
  }
  public function executeEdit(){
    if(isset($_GET['speaker'])){
      $this->speaker = $this->getRepository()->findJoinEvents($_GET['speaker']);
    }else{
      $this->speaker = new \Entities\Speaker();
    }
  }
  public function executeSave(){

  }
  /**
   * Returnerer SpeakerRepository med type hinting
   * @return \Entities\Repositories\SpeakerRepository
   */
  private function getRepository(){
    return $this->getEntityManager()->getRepository('\Entities\Speaker');
  }
}