<?php
namespace Entities;

/**
 * @Entity
 * @Table(name="event_types")
 */
class EventType extends LagetEntity{
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
  protected $name_no;
  /**
   * @Column(
   *  type="string",
   *  length="70"
   * )
   */
  protected $name_en;
  /**
   * @Column(
   *  type="string",
   *  length="150",
   *  nullable="true"
   * )
   */
  protected $description_no;
  /**
   * @Column(
   *  type="string",
   *  length="150",
   *  nullable="true"
   * )
   */
  protected $description_en;

  /**
   * @OneToMany(targetEntity="Event",mappedBy="type")
   * @var \Entities\Events
   */
  private $events;

  public function getId(){
    return $this->id;
  }
  public function getName($lang = NULL) {
    return $this->getI18n('name', $lang);
  }
  public function setName($name,$lang){
    $this->setI18n($name, 'name', $lang);
    return $this;
  }
  public function getDescription($lang = NULL){
    return $this->getI18n('description', $lang);
  }
  public function setDescription($description,$lang){
    $this->setI18n($description, 'description', $lang);
    return $this;
  }
}