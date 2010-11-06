<?php
namespace Entities;
/**
 * @Entity(repositoryClass="Entities\Repositories\EventRepository")
 * @Table(name="eventkalender")
 * @HasLifecycleCallbacks
 * @method \Entities\Event getRawValue()
 */
class Event extends LagetEntity {
  /**
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @ManyToOne(targetEntity="EventType",inversedBy="events")
   */
  private $type;
  /**
   * @Column(
   *  type="string",
   *  length="70"
   * )
   */
  protected $title_no;
  /**
   * @Column(
   *  type="string",
   *  length="70"
   * )
   */
  protected $title_en;
  /**
   * @Column(
   *  type="string",
   *  length="150",
   *  nullable="true"
   * )
   */
  protected $short_no;
  /**
   * @Column(
   *  type="string",
   *  length="150",
   *  nullable="true"
   * )
   */
  protected $short_en;
  /**
   * @Column(
   *  type="text",
   *  nullable="true"
   * )
   */
  protected $info_no;
  /**
   * @Column(
   *  type="text",
   *  nullable="true"
   * )
   */
  protected $info_en;
  /**
   * @Column(
   *  type="text",
   *  nullable="true"
   * ) 
   */
  private $internal_info;
  /**
   * @Column(
   *  type="string",
   *  length="255",
   *  nullable="true"
   * )
   */
  private $link;
  /**
   * @Column(
   *  name="startTS",
   *  type="datetime",
   *  nullable="true"
   * )
   * @var DateTime
   */
  private $start;
  /**
   * @Column(
   *  name="endTS",
   *  type="datetime"
   * )
   * @var DateTime
   */
  private $end;
  /**
   * @Column(
   *  type="boolean"
   * )
   * @var boolean
   */
  private $isPublic = false;
  /**
   * @Column(
   *  type="datetime"
   * )
   * @var \DateTime 
   */
  private $created_at;
  /**
   * @Column(
   *  type="datetime"
   * )
   * @var \DateTime
   */
  private $edited_at;
  /**
   * @Column(
   *  type="integer"
   * )
   * @Version
   */
  private $version;
  /**
   * @ManyToOne(targetEntity="Speaker",inversedBy="events")
   * @var Speaker
   */
  private $speaker;
  /**
   * @OneToMany(targetEntity="EventResponsibility", mappedBy="event", cascade={"persist", "remove"})
   * @var \Entities\EventResponsibility
   */
  private $responsibilities;
  /**
   *
   */
  public function __construct() {
    $this->responsibilities = new \Doctrine\Common\Collections\ArrayCollection();
  }
  /**
   * Get id
   *
   * @return integer $id
   */
  public function getId() {
    return $this->id;
  }
  public function setId($id) {
    $this->id = $id;
    return $this;
  }
  /**
   * Set title
   *
   * @param string $title
   * @param string $lang
   */
  public function setTitle($title,$lang) {
    $this->setI18n(strip_tags($title), 'title', $lang);
    return $this;
  }
  /**
   * Get title
   *
   * @param string $lang
   * @return string $title
   */
  public function getTitle($lang = NULL) {
    return $this->getI18n('title', $lang);
  }
  /**
   * Set short
   *
   * @param string $short
   * $param string $lang
   * $return \Entities\Event
   */
  public function setShort($short,$lang) {
    $this->setI18n(strip_tags($short), 'short', $lang);
    return $this;
  }

  /**
   * Get short
   *
   * @param string $lang
   * @return string $short
   */
  public function getShort($lang = NULL) {
    return $this->getI18n('short', $lang);
  }
  public function hasShort(){
    return strlen($this->short_en) || strlen($this->short_no);
  }
  /**
   *
   * @return EventType
   */
  public function getType() {
    return $this->type;
  }
  public function setType(\Entities\EventType $type) {
    $this->type = $type;
  }
  /**
   * Get info
   *
   * @param string $lang
   * @return text $info
   */
  public function getInfo($html = true, $lang = NULL) {
    if($html === 'edit') {
      return $this->getI18n('info', $lang);
    }elseif($html){
      return nl2br($this->getI18n('info', $lang),true);
    }
    return strip_tags($this->getI18n('info', $lang));
  }
  /**
   * Set info -
   *
   * @param text $info
   * @param text $lang
   */
  public function setInfo($info,$lang) {
    $pr = 'info_'.$lang;
    $this->$pr = $info;
    return $this;
  }
  public function hasInfo(){
    return strlen($this->info_en) || strlen($this->info_no);
  }
  public function getFullInfo($html = true, $lang = NULL) {
    if($html) {
      return $this->getShort($lang)."<br />\n".$this->getInfo($html, $lang);
    }else {
      return $this->getShort($lang)."\n".$this->getInfo($html, $lang);
    }
  }
  public function hasInternalInfo(){
    return strlen($this->internal_info) != 0;
  }
  public function setInternalInfo($internalInfo){
    $this->internal_info = $internalInfo;
    return $this;
  }
  public function getInternalInfo(){
    return $this->internal_info;
  }
  public function hasLink() {
    return isset($this->link);
  }
  public function setLink($link) {
    $this->link = $link;
    return $this;
  }
  public function getLink() {
    return $this->link;
  }
  /**
   * Hent et array med alle som har ansvar for hendelsen (ansvarlig, lyd, bar, kake osv,)
   * @return \Entities\EventResponsibility
   */
  public function getResponsibilities($type = NULL,$implode = false){
    if($type == NULL){
      return $this->responsibilities;
    }
    $responsibilities = array();
    foreach($this->responsibilities as $responsibility){
      if($responsibility->getResponsibility()->getName('no')== $type){
        $responsibilities[] = $responsibility;
      }
    }
    if($implode){
      return implode(' - ',array_map(function($var){return (string)$var;},$responsibilities));
    }
    return $responsibilities;
  }
  public function setResponsibility(\Entities\EventResponsibility $responsible) {
    $responsible->setEvent($this);
    $this->responsibilities[] = $responsible;
    return $this;
  }
  public function getSpeaker() {
    return $this->speaker;
  }
  public function setSpeaker(\Entities\Speaker $speaker) {
    $this->speaker = $speaker;
    return $this;
  }
  public function hasSpeaker() {
    return isset($this->speaker);
  }

  public function __tostring() {
    return $this->getTitle().' ('.trim($this->getStart('%e. %h. %Y').')');
  }
  /**
   * Hent versjonsnummeret denne hendelsen har.
   * (brukes for å hindre at noen kan redigere en hendelse samtidig og overskrve hverandre)
   * @return int
   */
  public function getVersion() {
    return $this->version;
  }
  public function setVersion($version){
    $this->version = $version;
    return $this;
  }
  public function setIsPublic($isPublic) {
    $this->isPublic = $isPublic;
    return $this;
  }
  public function isPublic(){
    return $this->isPublic;
  }
  public function setStart(\DateTime $start) {
    if(isset($this->start)){
      // Hack to make doctrine not assume that the end time has changed when it has not
      $this->start->setTimestamp($start->getTimestamp());
    }else{
      $this->start = $start;
    }
    return $this;
  }
  public function setEnd(\DateTime $end) {
    if(isset($this->end)){
      // Hack to make doctrine not assume that the end time has changed when it has not
      $this->end->setTimestamp($end->getTimestamp());
    }else{
      $this->end = $end;
    }
    return $this;
  }
  /**
   *
   * @param <type> $format
   * @return \DateTime
   */
  public function getStart($format = NULL) {
    if($this->start === NULL){
      return NULL;
    }
    return $this->formatDateTime($this->start, $format);
  }
  /**
   *
   * @param <type> $format
   * @return \DateTime
   */
  public function getEnd($format = NULL) {
    if($this->end === NULL){
      return NULL;
    }
    return $this->formatDateTime($this->end, $format);
  }
  /**
   * Få full dato og varighet på gjeldende språk
   * viser full dato for slutt bare hvis sluttdato er en annen enn startdato
   *
   * @return string
   */
  public function getFullDate() {
    if($this->start === NULL || $this->end === NULL){
      return NULL;
    }
    if($this->getStart('Ymd')==$this->getEnd('Ymd')) {
      // ikke vis slutt dato da den er lik som start dato
      return $this->getStart('%e. %B %Y %R').' - '.$this->getEnd('%R');
    }
    return $this->getStart('%e. %B %Y %R').' - '.$this->getEnd('%e. %B %Y %R');
  }
  /**
   * Få varigheten på en hendelse i dager
   * @return integer
   */
  public function getDays() {
    if($this->start === NULL || $this->end === NULL){
      return NULL;
    }
    return $this->start->diff($this->end,false)->days;
  }
  public function getCreated($format = null){
    if($this->created_at === NULL){
      return NULL;
    }
    return $this->formatDateTime($this->created_at, $format);
  }
  public function getEdited($format = null){
    if($this->edited_at === NULL){
      return NULL;
    }
    return $this->formatDateTime($this->edited_at, $format);
  }
  public function setCreated(\DateTime $created){
    $this->created_at = $created;
    return $this;
  }
  public function setEdited(\DateTime $edited){
    $this->edited_at = $edited;
    return $this;
  }
  public function getAddEventToGoogleCalendarLink(\Laget\Routing\RoutingInterface $routing = NULL) {
    $url =  'http://www.google.com/calendar/event?action=TEMPLATE'.
            '&amp;text='. urlencode($this->getTitle()).
            '&amp;dates='. urlencode(gmdate("Ymd\THis\Z",$this->start->getTimestamp())).'/'.urlencode(gmdate("Ymd\THis\Z",$this->end->getTimestamp())).
            '&amp;details='.urlencode($this->getFullInfo($html = false));
    if($routing) {
      $url.='&amp;sprop=website:'.urlencode($routing->showEvent($this));
    }
    $url .= '$amp;sprop=name:laget.net';
    return $url;
  }
  public function isValid(){

    return true;
  }
  /**
   * @PrePersist
   */
  public function preSave(){
    $this->created_at = new \DateTime();
    $this->edited_at = new \DateTime();
  }
  /**
   * @PreUpdate
   */
  public function preUpdate(){
    $this->edited_at->setTimestamp(time());
  }
  private function formatDateTime(\DateTime $datetime,$format){
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