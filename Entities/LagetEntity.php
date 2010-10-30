<?php
namespace Entities;
/*
 * Dette er en felles klasse som alle entities på laget.net skal extende
 * her kan vi legge felles kode som trengs i de fleste klasser 
 *  fx. funksjonalitet for å kunne ha flere språk.
 */
class LagetEntity{
  /**
   * et array med valideringsfeil på formen
   * @var array
   */
  protected $error = array();
   /**
   * Hjelpefunksjon for å hente info med riktig språk
   * @param <type> $feld
   * @param <type> $lang
   * @return \Entities\Event
   */
  protected  function getI18n($feld,$lang) {
    if($lang == null){
      $re = $feld.'_'.\Laget\Controller\BaseController::$__lang;
      if(!strlen($this->$re)) {
        $re = $feld.'_no';
      }
    }else{
      $re = $feld.'_'.$lang;
    }
    if(!property_exists($this, $re)){
      throw new \Exception(sprintf('Det finnes ikke noe internasjonalisert felt med navnet "%s" på klassen "%s", eller språket "%s" er ugyldig.',$feld,get_class($this),$lang));
    }
    return $this->$re;
  }
  protected function setI18n($string,$feld,$lang) {
    $re = $feld.'_'.$lang;
    if(!property_exists($this, $re)){
      throw new \Exception(sprintf('Det finnes ikke noe internasjonalisert felt med navnet "%s" på klassen "%s", eller språket "%s" er ugyldig.',$feld,get_class($this),$lang));
    }
    $this->$re = $string;
    return $this;
  }
  public function validateEmail($email,$feld) {
    if(!preg_match('', $email)){
      $this->error[$feld] = 'Eposten validerte ikke';
      return false;
    }
    return true;
  }
}