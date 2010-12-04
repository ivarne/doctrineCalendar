<?php
namespace Entities;

/**
 * Description of RegistrationTask
 *
 * @author ivarne
 * @Entity(repositoryClass="Entities\Repositories\RegistrationTaskRepository")
 * @Table(name="registration_task")
 */
class RegistrationTask extends LagetEntity {
  /**
   * @Column(type="integer")
   * @Id
   * @GeneratedValue
   */
  private $id;
  /**
   * @Column(type="string")
   */
  protected $name_no;
  /**
   * @Column(type="string")
   */
  protected $name_en;
  /**
   * @Column(type="text")
   */
  protected $description_no;
  /**
   * @Column(type="text")
   */
  protected $description_en;
  /**
   * @Column(type="integer")
   */
  private $num_available;
  /**
   * @ManyToOne(targetEntity="Event", inversedBy="registrationTasks")
   * @JoinColumn(nullable=false)
   * @var \Entities\Event
   */
  private $event;
  /**
   * @OneToMany(
   *  targetEntity="Registration",
   *  mappedBy="task"
   * )
   * @var \Entities\Registration
   */
  private $registrations;

  public function getId() {
    return $this->id;
  }
  public function setName($name,$lang = null) {
    $this->setI18n($name, 'name', $lang);
    return $this;
  }
  public function getName($lang = null) {
    return $this->getI18n('name',$lang);
  }
  public function getDescription($lang = null) {
    return $this->getI18n('description',$lang);
  }
  public function setDescription($description,$lang) {
    $this->setI18n($description, 'description', $lang);
    return $this;
  }
  public function setNumAvailable($numAvailable) {
    $this->num_available = $numAvailable;
    return $this;
  }
  public function getNumAvailable() {
    return $this->num_available;
  }

  /**
   * Set event
   *
   * @param \Entities\Event $event
   */
  public function setEvent(Event $event) {
    $this->event = $event;
  }

  /**
   * Get event
   *
   * @return \Entities\Event $event
   */
  public function getEvent() {
    return $this->event;
  }

  /**
   * Set registrations
   *
   * @param \Entities\Registration $registrations
   */
  public function setRegistrations(Registration $registrations) {
    $this->registrations = $registrations;
  }

  /**
   * Get registrations
   *
   * @return \Entities\Registration $registrations
   */
  public function getRegistrations() {
    return $this->registrations;
  }
  public function isFull(){
    return count($this->registrations) >= $this->num_available;
  }
}