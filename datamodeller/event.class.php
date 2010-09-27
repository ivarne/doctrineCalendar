<?php
/**
 * Description of kalenderclass
 * 
 * HUSK:
 * * hvis du skal bruke dataene i en annen context enn i html documenter
 *   må du huske å kalle Event::html = false; før du begynner å hente data
 *
 * @author ivarne
 */
class oldEvent {
  // klassekonstanter for å beskrive hendelsen brukes som en slags enum i databasen
  const FREDAGSMOTE = 1;
  const DUGNAD = 2;
  const TUR = 3;
  const BIBEL = 4;
  const VASSFJELLET = 5;
  const UTLEIE = 6;
  const RESERVASJONMEDLEM = 7;
  const UKJENT = 8;
  const UTLEIEVASSFJELLET = 9;

  public $event;
  public $id;
  public $startTS;
  public $sluttTS;
  public $created_at;
  public $edited_at;
  /**
   *
   * @var kalender_db
   */
  private $db;
  /**
   *
   * @var modx_abs
   */
  private $modx; // referanse til et objet som gir abstraksjon av modx funksjonalitet
  static public $escHtml = true; // setter om det skal returneres sanert html output

  /**
   *
   */
  public function __construct($row, modx_abs $modx,kalender_db $db) {
    // valider at hendelsen er ca riktig
    $this->validateRow($row);
    $this->event = $row;
    $this->startTS = strtotime($row['start']);
    $this->sluttTS = strtotime($row['slutt']);
    $this->created_at = strtotime($row['opprettet']);
    $this->edited_at = strtotime($row['endret']);

    $this->modx = $modx;
    $this->db = $db;
  }
  public function getId() {
    return $this->event['id'];
  }
  public function isPublic(){
    return $this->event['publisert'];
  }
  public function getTittel($lang = null) {

    return $this->htmlent($this->chooseLangString(
            array('no'=>'hendelse','en'=>'hendelse_en'),$lang));
  }
  public function hasKort(){
    return strlen($this->event['kort_info']) || strlen($this->event['kort_info_en']);
  }
  public function getKort($lang = null) {
    return $this->htmlent($this->chooseLangString(
            array('no'=>'kort_info','en'=>'kort_info_en'),$lang));
  }
  public function hasInfo(){
    return strlen($this->event['info']) || strlen($this->event['info_en']);
  }
  public function getInfo($lang = null) {
    $info = $this->chooseLangString(
            array('no'=>'info','en'=>'info_en'),$lang);
    if(!$this->html){
      $info = strip_tags($this->br2nl($info));
    }
    return $info;
  }
  public function hasInternInfo(){
    return strlen($this->event['intern_info']);
  }
  public function getInternInfo(){
    if(!$this->modx->userHasPermission('intern_info')){
      return;
    }
    return $this->event['inter_info'];
  }
  public function hasTaler(){
    return strlen($this->event['taler']);
  }
  public function getTaler() {
    return $this->htmlent($this->event['taler']);
  }
  public function getFullDate(){
    if(date('Ymd', $this->startTS) != date('Ymd', $this->sluttTS)){
      return strftime('%e. %h. %Y %R',$this->sluttTS).' - '.strftime('%e. %h. %Y %R',$this->sluttTS);
    }else{
      return strftime('%e. %h. %Y %R',$this->startTS).' - '.strftime('%R',$this->sluttTS);
    }
  }
  public function getDager(){
    $start = getdate($this->startTS);
    $end   = getdate($this->sluttTS);
    $dager = $end['yday']-$start['yday'];
    // ta hensyn til at hendelsen kan vare over nyttår
    if($end['year'] != $start['year']){
      if($start['year'] % 4 || $start['year'] == 2000){
        $dager += 365;
      }else{
        $dager += 366;
      }
    }
    return $dager;
  }
  public function getFullInfo(){
    if (0 == strlen($this->getKort())) {
      return $this->getInfo();
    }elseif(0 == strlen($this->getInfo())){
      return $this->getKort();
    }
    return $this->getKort().'<br>'.$this->getInfo();
  }
  static public function getEventTypesArray(){
    return array(
      Event::FREDAGSMOTE      => 'Fredagmsøte',
      Event::BIBEL            => 'Bibeltime',
      Event::DUGNAD           => 'Dugnad',
      Event::TUR              => 'Tur',
      Event::RESERVASJONMEDLEM=> 'Reservasjoner for øvinger Lovsangsband og andre mindre møter',
      Event::UTLEIE           => 'Utleie av Berg Prestegård',
      Event::VASSFJELLET      => 'Aktivitet på Vassjellkapellet',
      Event::UTLEIEVASSFJELLET=>  'Utleie av vassfjellkapellet',
      Event::UKJENT           => 'Ukjent hendelse (for import av gammel kalender)',
    );
  }
  /**
   * en enkel funksjon som velger riktig språk basert på et array og sender
   * feilmelding om den ikke finnes;
   * @param array $valg
   */
  private function chooseLangString($felt,$lang) {
    if($lang != NULL){
      return $this->event[$felt[$lang]];
    }
    if( 0<strlen($this->event[$felt[$this->modx->lang]])) {
      return $this->event[$felt[$this->modx->lang]];
    }
    return $this->event[$felt['no']];
  }
  private function htmlent($string) {
    if(self::$escHtml){
      return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    return $string;
  }
  private function br2nl($string){
    $enter = array( "<br>" , "<br/>" , "<BR>" , "<BR/>" , "<Br>" , "<Br/>" ,
                "<bR>" , "<bR/>" , "<br />" , "<BR />" , "<Br />" , "<bR />" , "\r");
    return str_replace($enter, "\n" , $string);
  }

  public function validateRow($row) {
    if( !array_diff_key(
      array_keys(array('id', 'hendelse','hendelse_en','start','slutt')), $row)
    ) {
      throw new Exception('En hendelse ble forsøkt initialisert med en ugyldig rad');
    }
  }
}
?>
