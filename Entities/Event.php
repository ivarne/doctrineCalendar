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
   *  length="70",
   *  nullable="true"
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
  protected $start;
  /**
   * @Column(
   *  name="endTS",
   *  type="datetime"
   * )
   * @var DateTime
   */
  protected $end;
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
   * @OneToMany(
   *  targetEntity="Registration",
   *  mappedBy="event"
   * )
   * @OrderBy({"name"="ASC"})
   * @var Entities\Registration
   */
  private $registrations;
  /**
   * @OneToMany(
   *  targetEntity="RegistrationTask",
   *  mappedBy="event"
   * )
   */
  private $registrationTasks;
  /**
   * @Column(type="smallint")
   * @var int
   */
  private $hasRegistration = 0;
  /**
   * @Column(
   *  type="datetime",
   *  nullable=true
   * )
   * @var \DateTime
   */
  private $registrationUntill;
  /**
   * @Column(
   *  type="datetime",
   *  nullable=true
   * )
   * @var \DateTime
   */
  private $registrationFrom;
  /**
   * @Column(
   *  type="text",
   *  nullable=true
   * )
   */
  protected $registration_mail_no;
  /**
   * @Column(
   *  type="text",
   *  nullable=true
   * )
   */
  protected $registration_mail_en;

  /**
   * @Column(
   *  type="integer",
   *  nullable=true
   * )
   */
  private $price_member;

  /**
   * @Column(
   *  type="integer",
   *  nullable=true
   * )
   */
  private $price_non_member;

  /**
   * @Column(
   *  type="array",
   *  nullable=true
   * )
   */
  public $extra;

  /**
   *
   */
  public function __construct() {
    $this->responsibilities = new \Doctrine\Common\Collections\ArrayCollection();
    $this->registrations = new \Doctrine\Common\Collections\ArrayCollection();
    $this->registrationTasks = new \Doctrine\Common\Collections\ArrayCollection();
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
    $info = $this->getI18n('info', $lang);
    $info = str_ireplace(array('<br>\n', '<br />\n', '</li>\n', '</tr>\n'), "\n", $info);
    $info = str_ireplace(array('<br>', '<br />', '</li>', '</tr>'), "\n", $info);
    $info = str_ireplace('<p>', "\n\n", $info);
    $info = str_ireplace("\n\n\n", "\n\n", $info);
    return strip_tags($info);
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
  public function getInternalInfo($html = false){
    if($html){
      return nl2br($this->internal_info);
    }
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
      if($responsibility->getResponsibility()->getId()== $type){
        $responsibilities[] = $responsibility;
      }
    }
    if($implode){
      return implode(' - ',$responsibilities);
    }
    return $responsibilities;
  }
  public function getRegistrationTasks(){
    return $this->registrationTasks;
  }
  public function getRegistrations(){
    return $this->registrations;
  }
  public function setHasRegistration($integer){
    $this->hasRegistration = $integer;
    return $this;
  }
  public function getHasRegistration(){
    return $this->hasRegistration;
  }
  public function hasRegistration(){
    if(!is_null($this->registrationFrom) && $this->registrationFrom->getTimestamp() > time()){
        return isset($_GET['show_registration']); // registration has not started yet
    }
    return $this->hasRegistration;
  }
  public function hasFullRegistration(){
    return $this->hasRegistration == 2 || $this->hasRegistration == 3;
  }
  public function hasRegistrationTasks(){
    return $this->hasRegistration == 3;
  }
  public function hasOpenRegistration(){
    if(isset($_GET['show_registration'])){
      return true;
    }
    if(!$this->hasRegistration){
      return false;
    }
    if(!is_null($this->registrationUntill)){
      return $this->registrationUntill->getTimestamp() > time();
    }
    return $this->start->getTimestamp() > time();
  }
  public function setResponsibility(\Entities\EventResponsibility $responsible) {
    $responsible->setEvent($this);
    $this->responsibilities[] = $responsible;
    return $this;
  }
  public function hasTranslator(){
    return count($this->getResponsibilities(Responsibility::Overseting)) > 0;
  }
  public function hasResponsible(){
    return conut($this->getResponsibilities(Responsibility::Ansvarlig)) > 0;
  }
  public function isRegistred(User $user){
    foreach($this->registrations as $reg){
      if($reg->getUser() && $reg->getUser()->getId()==$user->getId())
              return true;
    }
    return false;
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
    return $this->getTitle().' ('.trim($this->getStart('%e. %h %Y').')');
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
    $this->setDateTime('start', $start);
    return $this;
  }
  public function setEnd(\DateTime $end) {
    $this->setDateTime('end', $end);
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
    public function getMail($lang = null){
    return $this->getI18n('registration_mail', $lang);
  }
  public function setMail($mail,$lang){
    $this->setI18n($mail, 'registration_mail', $lang);
    return $this;
  }
  public function getTotalIncome(){
    if(false){
      $reg = new Registration();
    }
    $total = 0;
    foreach($this->getRegistrations() as $reg){
      if($reg->getPayedAmount()){
        $total += $reg->getPayedAmount();
      }else{
        $total += $reg->isMember()?$this->price_member : $this->price_non_member;
      }
    }
    return $total;
  }
  public function getNetPaymentError(){
    if(false){
      $reg = new Registration();
    }
    $error = 0;
    foreach($this->getRegistrations() as $reg){
      $error += $reg->getPayedAmount() - ($reg->isMember()?$this->price_member : $this->price_non_member);
    }
    return $error;
  }
  public function getTotalPayment(){
    if(false){
      $reg = new Registration();
    }
    $total = 0;
    foreach($this->getRegistrations() as $reg){
      $total += $reg->getPayedAmount();
    }
    return $total;
  }
  public function getNonPayedRegistration(){
    if(false){
      $reg = new Registration();
    }
    $nonpayed = array();
    foreach($this->getRegistrations() as $reg){
      if((integer)$reg->getPayedAmount() == 0){
        $nonpayed[] = $reg;
      }
    }
    return $nonpayed;
  }
  public function getErrorPayedRegistrations(){
    if(false){
      $reg = new Registration();
    }
    $nonpayed = array();
    foreach($this->getRegistrations() as $reg){
      if($reg->getPayedAmount() && ((integer)$reg->getPayedAmount() - ($reg->isMember()?$this->price_member : $this->price_non_member) != 0)){
        $nonpayed[] = $reg;
      }
    }
    return $nonpayed;
  }
  public function getPaymentDistribution(){
    if(false) $reg = new Registration();// autocomplete
    $payedAmmount = array(
        $this->getPriceMember()    => array(0,0),
        $this->getPriceNonMember() => array(0,0)
    );
    foreach($this->getRegistrations() as $reg){
      if(is_null($reg->getPayedAmount())){
        $payedAmmount[$reg->getPrice()][1]++;
        continue;
      }
      if(!isset($payedAmmount[$reg->getPayedAmount()])){
        $payedAmmount[$reg->getPayedAmount()] = array(0,0);
      }
      $payedAmmount[$reg->getPayedAmount()][0]++;
      $payedAmmount[$reg->getPrice()][1]++;
    }
    ksort($payedAmmount);
    return $payedAmmount;
  }
  public function getNumPayments(){
      if(false) $reg = new Registration();//autocomplete
      $num = 0;
      foreach ($this->getRegistrations() as $reg) {
          if($reg->getPayedAmount()){
              $num++;
          }
      }
      return $num;
  }
  public function hasPayment(){
    return isset($this->price_member) && isset($this->price_non_member);
  }
  public function getPriceMember(){
    return $this->price_member;
  }
  public function setPriceMember($price){
    $this->price_member = $price;
    return $this;
  }
  public function getPriceNonMember(){
    return $this->price_non_member;
  }
  public function setPriceNonMember($price){
    $this->price_non_member = $price;
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
    $error = array();
    if(strlen($this->title_no)<4){
      $error[] = 'Du må gi hendelsen et norsk navn på mer enn tre bokstaver';
    }
    if(strlen($this->title_no)>70){
      $error[]= 'Den norske tittelen er for lang';
    }
    if(strlen($this->title_en)>70){
      $error[]= 'Den engelske tittelen er for lang';
    }
    if(strlen($this->short_no)>150){
      $error[]= 'Kort info skal være kortere enn 150 tegn. Den norske har '.strlen($this->short_no);
    }
    if(strlen($this->short_en)>150){
      $error[]= 'Kort info skal være kortere enn 150 tegn. Den engelske har '.strlen($this->short_no);
    }
    return $error;
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
}
