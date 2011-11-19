<?php
namespace Entities;


/**
 * ModxWebUserAttributes
 *
 * @Table(name="modx_web_user_attributes")
 * @Entity(repositoryClass="Entities\Repositories\UserRepository")
 * @method \Entities\User getRawValue()
 */
class User_atteributes {
  /**
   * @var integer $id
   *
   * @Column(name="id", type="integer", nullable=false)
   * @Id
   * @GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * @OneToOne(targetEntity="User", inversedBy="atteributes")
   * @JoinColumn(name="internalKey", referencedColumnName="id",nullable=false)
   */
  private $internalkey;

  /**
   * @var string $fullname
   *
   * @Column(name="fullname", type="string", length=100, nullable=false)
   */
  private $fullname;

  /**
   * @var integer $role
   *
   * @Column(name="role", type="integer", nullable=false)
   */
  private $role;

  /**
   * @var string $email
   *
   * @Column(name="email", type="string", length=100, nullable=false)
   */
  private $email;

  /**
   * @var string $phone
   *
   * @Column(name="phone", type="string", length=100, nullable=true)
   */
  private $phone;

  /**
   * @var string $mobilephone
   *
   * @Column(name="mobilephone", type="string", length=100, nullable=true)
   */
  private $mobilephone;

  /**
   * @var integer $blocked
   *
   * @Column(name="blocked", type="integer", nullable=false)
   */
  private $blocked;

  /**
   * @var integer $blockeduntil
   *
   * @Column(name="blockeduntil", type="integer", nullable=false)
   */
  private $blockeduntil;

  /**
   * @var integer $blockedafter
   *
   * @Column(name="blockedafter", type="integer", nullable=false)
   */
  private $blockedafter;

  /**
   * @var integer $logincount
   *
   * @Column(name="logincount", type="integer", nullable=false)
   */
  private $logincount;

  /**
   * @var integer $lastlogin
   *
   * @Column(name="lastlogin", type="integer", nullable=false)
   */
  private $lastlogin;

  /**
   * @var integer $thislogin
   *
   * @Column(name="thislogin", type="integer", nullable=false)
   */
  private $thislogin;

  /**
   * @var integer $failedlogincount
   *
   * @Column(name="failedlogincount", type="integer", nullable=false)
   */
  private $failedlogincount;

  /**
   * @var string $sessionid
   *
   * @Column(name="sessionid", type="string", length=100, nullable=false)
   */
  private $sessionid;

  /**
   * @var integer $dob
   *
   * @Column(name="dob", type="integer", nullable=false)
   */
  private $dob;

  /**
   * @var integer $gender
   *
   * @Column(name="gender", type="integer", nullable=false)
   */
  private $gender;

  /**
   * @var string $country
   *
   * @Column(name="country", type="string", length=5, nullable=false)
   */
  private $country;

  /**
   * @var string $state
   *
   * @Column(name="state", type="string", length=25, nullable=false)
   */
  private $state;

  /**
   * @var string $zip
   *
   * @Column(name="zip", type="string", length=25, nullable=false)
   */
  private $zip;

  /**
   * @var string $fax
   *
   * @Column(name="fax", type="string", length=100, nullable=false)
   */
  private $fax;

  /**
   * @var string $photo
   *
   * @Column(name="photo", type="string", length=255, nullable=false)
   */
  private $photo;

  /**
   * @var string $comment
   *
   * @Column(name="comment", type="string", length=255, nullable=false)
   */
  private $comment;

  /**
   * @var string $adrTrheim
   *
   * @Column(name="adr_trheim", type="string", length=100, nullable=true)
   */
  private $adrTrheim;

  /**
   * @var string $pnrTrheim
   *
   * @Column(name="pnr_trheim", type="string", length=8, nullable=true)
   */
  private $pnrTrheim;

  /**
   * @var string $adrHeim
   *
   * @Column(name="adr_heim", type="string", length=100, nullable=true)
   */
  private $adrHeim;

  /**
   * @var string $pnrHeim
   *
   * @Column(name="pnr_heim", type="string", length=8, nullable=true)
   */
  private $pnrHeim;

  /**
   * @var string $tlfHeim
   *
   * @Column(name="tlf_heim", type="string", length=15, nullable=true)
   */
  private $tlfHeim;

  /**
   * @var string $studiested
   *
   * @Column(name="studiested", type="string", length=100, nullable=true)
   */
  private $studiested;

  /**
   * @var date $startaar
   *
   * @Column(name="startaar", type="date", nullable=true)
   */
  private $startaar;

  /**
   * @var date $fodselsdato
   *
   * @Column(name="fodselsdato", type="date", nullable=true)
   */
  private $fodselsdato;

  /**
   * @var integer $hemmelig
   *
   * @Column(name="hemmelig", type="integer", nullable=true)
   */
  private $hemmelig;

  /**
   * @var integer $godkjent
   *
   * @Column(name="godkjent", type="integer", nullable=false)
   */
  private $godkjent;


  public function getId() {
    return $this->internalkey;
  }
  public function getName() {
    return $this->fullname;
  }
  public function getFirstName(){
    $names = explode(' ', $this->fullname, 2);
    return $names[0];
  }
  public function getLastName(){
    $names = explode(' ', $this->fullname, 2);
    return @$names[1];
  }
  public function getEmail() {
    return $this->email;
  }
  public function getTlf() {
    return $this->mobilephone;
  }
  public function isSecret() {
    return $this->hemmelig;
  }
  public function __toString(){
    return htmlspecialchars( $this->getName(),\ENT_QUOTES , 'UTF-8');
  }
}