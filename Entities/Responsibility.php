<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\Repositories\ResponsibilityRepository")
 * @Table(name="responsibility")
 * @method \Entities\Responsibility getRawValue()
 */
class Responsibility extends LagetEntity{

  const Ansvarlig = 1;
  const Lovsang = 2;
  const Leder = 3;
  const Kjokken = 4;
  const Teknikkker = 5;
  const Overseting = 6;
  /**
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @Column(
   *
   * )
   * @var string
   */
  protected $name_no;
  /**
   * @Column()
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

  public function getId(){
    return $this->id;
  }
  public function getName($lang = NULL){
    return $this->getI18n('name', $lang);
  }
  public function setName($name,$lang){
    $this->setI18n($name, 'name', $lang);
    return $this;
  }
  public function getDescription($lang = NULL){
    return $this->getI18n('description', $lang);
  }
  public function setDescription($description,$lang) {
    $this->setI18n($description, 'description', $lang);
  }
}