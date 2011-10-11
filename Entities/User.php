<?php
namespace Entities;


/**
 * ModxWebUserAttributes
 *
 * @Table(name="modx_web_users")
 * @Entity(repositoryClass="Entities\Repositories\UserRepository")
 * @method \Entities\User getRawValue()
 */
class User {
  /**
   * @var integer $id
   *
   * @Column(name="id", type="integer", nullable=false)
   * @Id
   * @GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @Column(type="string", length="100", nullable=false, unique=true)
   */
  private $username;
  /**
   * @Column(type="string", length="100", nullable=false)
   */
  private $password;
  /**
   * @Column(type="string", length="100", nullable=false)
   */
  private $cachepwd;
  /**
   * @OneToMany(
   *  targetEntity="EventResponsibility",
   *  mappedBy="user"
   * )
   * @var Entities\Event
   */
  private $eventResponsibilites;
  /**
   * @OneToMany(
   *  targetEntity="Registration",
   *  mappedBy="user"
   * )
   * @var Entities\Registration
   */
  private $registrations;
  /**
   * @OneToOne(targetEntity="User_atteributes", mappedBy="internalkey")
   * @var \Entities\User_atteributes
   */
  private $atteributes;

  public function getId() {
    return $this->id;
  }
  public function getName() {
    return $this->atteributes->getName();
  }
  public function getFirstName() {
    return $this->atteributes->getFirstName();
  }
  public function getLastName() {
    return $this->atteributes->getLastName();
  }
  public function getEmail() {
    return $this->atteributes->getEmail();
  }
  public function getTlf() {
    return $this->atteributes->getTlf();
  }
  public function isSecret() {
    return $this->atteributes->isSecret();
  }
  public function isMember() {
    return $this->atteributes->isMember();
  }
  public function __toString() {
    return htmlspecialchars( $this->getName(),\ENT_QUOTES , 'UTF-8');
  }
}