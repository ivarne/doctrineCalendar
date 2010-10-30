<?php
namespace Laget\Controller;

class SpeakerViewController extends BaseController
{
  public function executeList(){
    $this->speakers = $this->getRepository()->findAll();
    return $this->render('listSpeakers');
  }
  public function executeShow(){
    $this->speaker = $this->getRepository()->find((int)$_GET['speakerId']);
    if($this->speaker == NULL){
      return 'Det finnes ingen taler med id='.htmlspecialchars($_GET['speakerId'], \ENT_QUOTES, 'UTF-8');
    }
    return $this->render('showSpeaker');
  }

  /**
   * Returnerer SpeakerRepository med type hinting
   * @return \Entities\Repositories\SpeakerRepository
   */
  private function getRepository(){
    return $this->getEntityManager()->getRepository('\Entities\Speaker');
  }
}