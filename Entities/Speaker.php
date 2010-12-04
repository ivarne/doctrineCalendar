<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\Repositories\SpeakerRepository")
 * @Table(name="speakers")
 * @method \Entities\Speaker getRawValue()
 */
class Speaker extends LagetEntity{
    /**
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @Column(
   *  type="string",
   *  length="70"
   * )
   */
  private $name;
  /**
   * @Column(
   *  type="string",
   *  length="70",
   *  nullable="true"
   * )
   */
  private $email;
  /**
   * @Column(
   *  type="string",
   *  length="70",
   *  nullable="true"
   * )
   */
  private $telephone;
  
  /**
   * @Column(
   *  type="text",
   *  nullable="true"
   * )
   */
  protected $about_no;
  /**
   * @Column(
   *  type="text",
   *  nullable="true"
   * )
   */
  protected $about_en;
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
   *  type="integer",
   *  nullable="true"
   * )
   * @Version
   */
  private $version;
  /**
   * @OneToMany(
   *  targetEntity="Event",
   *  mappedBy="speaker"
   * )
   * @OrderBy({"start"="DESC"})
   * @var Entities\Event
   */
  private $events;

  public function  __construct($name) {
    $this->name = $name;
    $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    $this->created_at = new \DateTime();
    $this->edited_at = new \DateTime();
  }
  public function getId(){
    return $this->id;
  }
  public function getName() {
    return $this->name;
  }
  public function setName($name){
    $this->name = $name;
    return $this;
  }
  public function getTelephone(){
    return $this->telephone;
  }
  public function setTelephone($telephone){
    $this->telephone = $telephone;
    return $this;
  }
  public function getEmail(){
    return $this->email;
  }
  public function setEmail($email){
    $this->validateEmail($email,'email');
    $this->email = $email;
    return $this;
  }
  public function getAbout($lang = NULL){
    return $this->getI18n('about', $lang);
  }
  public function setAbout($about,$lang){
    $this->setI18n($about, 'about', $lang);
    return $this;
  }
  public function setEdited(\DateTime $d){
    $this->edited_at = $d;
  }
  public function getVersion(){
    return $this->version;
  }
  /**
   *
   * @return \Entities\Event
   */
  public function getEvents(){
    return $this->events;
  }
  public function setNumEvents($num){
    $this->numEvents = $num;
  }
  public function getNumEvents(){
    if(isset($this->numEvents)){
      return $this->numEvents;
    }
    return $this->events->count();
  }
  public function getNumFutureSpeaches(){
    $num = 0;
    $now = new \DateTime();
    foreach($this->events as $event){
      if($event->getStart()>$now)
              $num++;
    }
    return $num;
  }
  public function getNumPastSpeaches() {
    $num = 0;
    $now = new \DateTime();
    foreach($this->events as $event){
      if($event->getStart()<$now)
              $num++;
    }
    return $num;
  }
  /**
   *
   * @todo Implement
   */
  public function isValid(){
    return true;
  }
  public function __tostring(){
    return $this->getName();
  }
}