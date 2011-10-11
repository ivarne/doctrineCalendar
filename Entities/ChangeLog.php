<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\Repositories\ChangeLogRepository")
 * @Table(name="change_log")
 * @method \Entities\ChangeLog getRawValue()
 */
class ChangeLog extends LagetEntity{
  /**
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;

  /**
   * @Column(
   *   type="string",
   *   nullable=false
   * )
   */
  private $entity;
  /**
   * @Column(
   *   type="integer",
   *   nullable=false
   * )
   */
  private $entity_id;
  /**
   * @ManyToOne(targetEntity="User",inversedBy="eventResponsibilites")
   * @var Entities\User
   */
  private $user;
  /**
   * @Column(
   *   type="datetime",
   *   nullable=false
   * )
   * @var \DateTime
   */
  private $time;

  /**
   * @Column(
   *  type="array",
   *  nullable=false
   * )
   */
  protected $data;


  public function  __construct(LagetEntity $entity, User $user) {
    $this->entity    = get_class($entity);
    switch ($this->entity) {
      case '\Entities\Event':
          $this->data = $this->eventToArray($entity);
        break;

      default:
        throw new Exception('Ukjent entity '.$this->entity);
        break;
    }
    $this->entity_id = $entity->getId();
    $this->user      = $user;
    $this->time      = new \DateTime();
  }
  private function eventToArray(Event $event){

  }
}