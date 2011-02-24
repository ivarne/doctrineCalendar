<?php
namespace Entities;

/**
 * Klasse som holder en påmelding for en bruker til et arrangement
 *
 * @author ivarne
 * @Entity
 * @Table(name="registration")
 */
class Registration extends LagetEntity {
  /**
   * @id
   * @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @Column(type="string")
   */
  private $name;
  /**
   * @Column(type="string")
   */
  private $tlf;
  /**
   * @Column(type="string")
   */
  private $email;
  /**
   * @Column(
   *  type="text",
   *  nullable=true
   * )
   */
  private $comment;
  /**
   * @column(
   *  type="string",
   *  length=5,
   *  nullable=true
   * )
   * @var string 
   */
  private $lang;
  /**
   * @Column(
   *  type="integer"
   * )
   */
  private $public = 0;
  /**
   * @Column(
   *  type="datetime",
   *  nullable=false
   * )
   */
  protected $created_at;
  /**
   * @Column(
   *  type="datetime",
   *  nullable=false
   * )
   */
  protected $updated_at;
  /**
   * @Column(
   *  type="integer",
   *  nullable=true
   * )
   */
  protected $payed_amount;
  /**
   * @ManyToOne(targetEntity="User",inversedBy="registrations")
   * @var Entities\User
   */
  private $user;
  /**
   * @ManyToOne(targetEntity="Event",inversedBy="registrations")
   * @var Entities\Event
   */
  private $event;
  /**
   * @ManyToOne(
   *  targetEntity="RegistrationTask",
   *  inversedBy="registrations"
   * )
   * @var Entities\RegistrationTask
   */
  private $task;


  public function getId(){
    return $this->id;
  }
  public function getName(){
    return $this->name;
  }
  public function setName($name){
    $this->name = $name;
    return $this;
  }
  public function getTlf(){
    return $this->tlf;
  }
  public function setTlf($tlf){
    $this->tlf = $tlf;
    return $this;
  }
  public function getEmail(){
    return $this->email;
  }
  public function setEmail($email){
    $this->email = $email;
    return $this;
  }
  public function getComment(){
    return $this->comment;
  }
  public function setComment($comment){
    $this->comment = $comment;
    return $this;
  }
  public function isPaymentOk(){
    if($this->hasUser() && $this->getUser()->isMember()){
      return $this->getEvent()->getPriceMember() == $this->payed_amount;
    }
    //ikke medlem
    return $this->getEvent()->getPriceNonMember() == $this->payed_amount;
  }
  public function isMember(){
    if(!$this->hasUser()){
      return false;
    }
    return $this->getUser()->isMember();
  }
  public function getPayedAmount(){
    return $this->payed_amount;
  }
  public function setPayedAmount($payed_amount){
    $this->payed_amount = $payed_amount;
    return $this;
  }
  public function hasUser(){
    return isset($this->user);
  }
  public function getUser(){
    return $this->user;
  }
  public function setUser(User $user){
    $this->user = $user;
    return $this;
  }
  /**
   *
   * @return \Entities\Event
   */
  public function getEvent(){
    return $this->event;
  }
  public function setEvent(Event $event){
    $this->event = $event;
    return $this;
  }
  /**
   *
   * @return \Entities\RegistrationTask
   */
  public function getTask(){
    return $this->task;
  }
  public function setTask(RegistrationTask $task) {
    $this->task = $task;
  }
  public function setPublic($public){
    $this->public = $public;
    return $this;
  }
  public function getPublic(){
    $arr = array(0=>'se alle påmeldte',1=>'logged inn',2=>'alle');
    return $arr[$this->public];
  }
  public function setLang($lang){
    $this->lang = $lang;
    return $this;
  }
  public function getLang(){
    return $this->lang;
  }
  /**
   *
   * @todo FIKS real validation.
   */
  public function isValid(){
    return true;
  }
}
?>
