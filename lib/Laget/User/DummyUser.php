<?php
namespace Laget\User;

class DummyUser implements UserInterface{
  private $em;
  public function __construct(\Doctrine\ORM\EntityManager $em){
    $this->em = $em;
  }
  public function hasPermission($permission){
    return true;
  }
  public function getId(){
    return 151;
  }

  public function isLoggedIn(){
    return true;
  }
  public function getName(){
    return 'Ivar Nesje';
  }
  public function getEmail(){
    return 'ivarne@gmail.com';
  }
  public function getTelephone(){
    return '45457886';
  }
  public function isMember(\DateTime $time = NULL){
    return true;
  }
  public function getLanguage(){
    if(isset($_GET['lang']) && strlen($_GET['lang'])==2){
      return $_GET['lang'];
    }
    return 'no';
  }
  public function getDoctrineUser(){
    return $this->getUserRepository()->find($this->getId());
  }
  /**
   *
   * @return \Entities\Repositories\UserRepository
   */
  private function getUserRepository(){
    return $this->em->getRepository('\Entities\User');
  }
}