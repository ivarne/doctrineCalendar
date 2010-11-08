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
    if(strlen($string) !== 0){
      $this->$re = $string;
    }
    return $this;
  }
  public function validateEmail($email,$feld) {
    if(strpos( $email,'@') === false){
      $this->error[$feld] = 'Eposten validerte ikke';
      return false;
    }
    return true;
  }
  public function getUpdatedAt($format = null){
    return $this->formatDateTime($this->updated_at, $format);
  }
  public function setUpdatedAt(\DateTime $updated_at){
    if(!property_exists($this, 'updated_at')){
      throw new \Exception('Det finnes intet felt "updated_at" på klasse "'.get_class($this).'"');
    }
    $this->updated_at = $updated_at;
    return $this;
  }
  public function getCreatedAt($format=null){
    if(!property_exists($this, 'created_at')){
      throw new \Exception('Det finnes intet felt "created_at" på klasse "'.get_class($this).'"');
    }
    return $this->formatDateTime($this->created_at, $format);
  }
  public function setCreatedAt(\DateTime $created_at){
    if(!property_exists($this, 'created_at')){
      throw new \Exception('Det finnes intet felt "created_at" på klasse "'.get_class($this).'"');
    }
    $this->created_at = $created_at;
    return $this;
  }
  protected function formatDateTime(\DateTime $datetime,$format){
    if($format == NULL) {
      return $datetime;
    }

    if(strpos($format,'%')!==false) {
      // fiks ø i lørdag og søndag
      if(\Laget\Controller\BaseController::$__lang == 'no'
            && (strpos($format,'%a')!==false || strpos($format,'%A')!==false)) {
        switch ($datetime->format('w')) {
          case 0:
            $format = strtr($format,array('%a'=>'s&oslash;n','%A'=>'s&oslash;ndag'));
            break;
          case 6:
            $format = strtr($format,array('%a'=>'l&oslash;r','%A'=>'l&oslash;rdag'));
        }
      }
      return strftime($format, $datetime->getTimestamp());
    }
    return $datetime->format($format);
  }
}