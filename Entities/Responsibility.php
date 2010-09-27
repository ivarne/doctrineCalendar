<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\Repositories\ResponsibilityRepository")
 * @Table(name="responsibility")
 */
class Responsibility extends LagetEntity{
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