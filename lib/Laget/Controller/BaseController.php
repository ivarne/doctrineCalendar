<?php
namespace Laget\Controller;

abstract class BaseController{
  /**
   * Et array med oversettelser fra Norsk til engelsk
   * @var array
   */
  static public $translations;
  // array for å lagre setninger som ikke har oversettelse på det aktuelle språket
  static public $notTranslatable = array();

  // språket siden det er vanskelig å sende det som parameter alle veier
  static public $__lang;
  /**
   * språket brukeren har valgt
   * @var \Laget\User\UserInterface
   */
  protected $user;

  /**
   *
   * @var \Laget\Routing\RoutingInterface 
   */
  protected $routing;
  /**
   *
   * @var \Doctrine\ORM\EntityManager
   */
  protected $em;
  /**
   * Inneholder et array med alle klasser og tekst
   * som skal gjøres tilgjenelig for en template fil.
   *
   * @var array
   */
  private $viewVariables = array();

  public function  __construct(\Doctrine\ORM\EntityManager $em, \Laget\Routing\RoutingInterface $routing, \Laget\User\UserInterface $user) {
    $this->em = $em;
    $this->routing = $routing;
    $this->user = $user;
    $this->lang = $this->user->getLanguage();
    self::$__lang = $this->lang;
    if($this->lang != 'no'){
      require dirname(__FILE__).'/../../../translations/'.$this->lang.'.php';
      self::$translations = $translations;
    }
    $this->setLocale();
  }
  protected function getEntityManager(){
    return $this->em;
  }
  protected function getUser(){
    return $this->user;
  }
  public function execute($action){
    try{
      if(!is_callable(array($this,'execute'.$action))){
        throw new \Exception('Ingen gyldig action på '.get_class($this).' med navn execute'.$action);
      }
      return call_user_func_array(array($this,'execute'.$action),array());
    }catch (simpleException $e){
      echo '<div class="error">'.$e->getMessage().'</div>';
    }catch (\Exception $e){
      echo 'Det skjedde en feil, vær vennlig å sende en kort beskrivelse av hva du gjorde sammen med denne feilmeldingen til web@laget.net';
      echo '<div><br><pre>';
      echo 'Melding: '.$e->getMessage()."\n";
      echo 'Fil: '. $e->getFile().' at line: '.$e->getLine()."\n";
      echo $e->getTraceAsString();
      echo '</pre></div>';
    }
  }
  /**
   * Enkel funksjon som gjør alle variablene som er satt i klassen
   * ved $this->variable tilgjengelig og inkluderer template fila.
   *
   * @param string $template
   * @return stirng
   */
  protected function render($template){
    try{
      $templateFile = __DIR__.'/../../../templates/'.$template.'.php';
      if (!file_exists($templateFile)){
        return '<big><strong>Det skjedde en feil!</strong></big><br> Send en mail til web@laget.net og forklar hva du gjorde og at "'.$templateFile.'" ikke eksisterer.';
      }
      extract($this->viewVariables);
      $routing = $this->routing;
      $user = $this->user;
      require $templateFile;

      // gi brukeren varsel om setninger på siden som ikke er oversatt.
      if(!empty(self::$notTranslatable)){
        echo '<div class="error">Disse setningene kunne ikke oversettes<br>Legg dem til i oversettelsesfila<pre>';
        foreach (self::$notTranslatable as $string) {
          echo "'$string' => '',\n";
        }
        echo '</pre></div>';
      }
    }catch (Exception $e){
      echo 'Det skjedde en feil i templaten, vær vennlig å sende en kort beskrivelse av hva du gjorde sammen med denne feilmeldingen til web@laget.net';
      echo '<div><pre>';
      echo 'Melding: '.$e->getMessage()."\n";
      echo 'Fil: '. $e->getFile().' at line: '.$e->getLine()."\n";
      echo $e->getTraceAsString();
      echo '</pre></div>';
    }

  }
  private function setLocale(){
    setlocale(LC_ALL , strtolower($this->lang)=='no' ? 'no_NO.UTF-8':'en_GB.UTF-8');
  }
  /**
   * Funksjon som gjør det mulig å gjøre variabler tilgjengelige i et view
   * fra en action
   *
   * i action:
   * $this->events = new Event('parameter1','parameter2')
   *
   * og i viewet er den tilgjenelig som en variabel:
   *
   * @param string $name
   * @param mixed $value
   */
  public function   __set($name,  $value) {
    $this->viewVariables[$name] = $value;
  }
  public function  __get($name) {
    return $this->viewVariables[$name];
  }
}
class simpleException extends \Exception{}
