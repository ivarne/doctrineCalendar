<?php

namespace Entities;
/**
 * @Entity
 * @Table(name="event_responsibility")
 * @method \Entities\EventResponsibility getRawValue()
 */
class EventResponsibility {
  /**
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @ManyToOne(
   *   targetEntity="Event",
   *   inversedBy="responsibilities"
   * )
   * @var Event
   */
  private $event;
  /**
   * @ManyToOne(targetEntity="Responsibility")
   * @var \Entities\Responsibility
   */
  private $responsibility;
  /**
   * @ManyToOne(targetEntity="User",inversedBy="eventResponsibilites")
   * @var Entities\User
   */
  private $user;
  /**
   * @Column(
   *  nullable="true"
   * )
   */
  private $comment;

  public function  __construct(Responsibility $resp, $user = NULL, $comment = NULL) {
    $this->setResponsibility($resp);
    if($user instanceof \Entities\User){
      $this->setUser($user);
    }
    $this->setComment($comment);
  }
  public function getId(){
    return $this->id;
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
  }
  public function setResponsibility(Responsibility $responsibility){
    $this->responsibility=$responsibility;
  }
  /**
   *
   * @return \Entities\Responsibility
   */
  public function getResponsibility(){
    return $this->responsibility;
  }
  /**
   * Hent brukeren som har dette ansvaret for hendelsen
   * @return \Entities\User
   */
  public function getUser(){
    return $this->user;
  }
  public function setUser(\Entities\User $user){
    $this->user = $user;
  }
  public function __tostring(){
    if(isset($this->user)&&isset($this->comment)){
      return (string)$this->user.' ('.$this->comment.')';
    }elseif(isset($this->user)){
      return (string)$this->user;
    }
    return (string)$this->comment;
  }
  public function getComment(){
    return $this->comment;
  }
  public function setComment($comment){
    if(strlen($comment) == 0 ){
      $this->comment = NULL;
    }else{
      $this->comment = $comment;
    }
    return $this;
  }
  public function hasComment(){
    return strlen($this->comment);
  }
}