<?php
namespace Laget\User;

class ModxUser implements UserInterface{
  /**
   * @var DocumentParser
   */
  private $modx;
  /**
   *
   * @var \Doctrine\ORM\EntityManager
   */
  private $em;
  /**
   *
   * @var \Entities\User
   */
  private $doctrineUser;
  private $cache;
  public function __construct(\Doctrine\ORM\EntityManager $em){
    global $modx;
    $this->modx = $modx;
    $this->em = $em;
  }
  public function hasPermission($permission){
    if($permission == 'alle')
       return true;
    if($permission == 'logged inn')
        return $this->isLoggedIn();
    $groups = $this->modx->getUserDocGroups(true);
    if($groups == null){
      return false;
    }
    switch ($permission) {
      case 'intern_info':
        return in_array('programbehandling',$groups);
      case 'se upubliserte':
        return in_array('programbehandling',$groups);
      case 'redigere hendelser':
        return in_array('programbehandling',$groups);
      case 'se alle påmeldte':
        return in_array('programbehandling',$groups);
      case 'member':
        return $this->isMember();
      case 'update_registration_paymens':
        return in_array('testsider',$groups);
      default:
        throw new \Exception('hasPermission kalt med ugyldig permission ('.$permission.')');
    }
  }
  public function getId(){
    return $this->modx->getLoginUserID();
  }
  public function isLoggedIn(){
    return $this->modx->userLoggedIn() && $this->getDoctrineUser();
  }
  public function getName(){
    if(!$this->cache){
      $this->cache = $this->modx->getWebUserInfo($this->getId());
    }
    return $this->cache['fullname'];
  }
  public function getEmail(){
    if(!$this->cache){
      $this->cache = $this->modx->getWebUserInfo($this->getId());
    }
    return $this->cache['email'];
  }
  public function getTelephone(){
    if(!$this->cache){
      $this->cache = $this->modx->getWebUserInfo($this->getId());
    }
    return $this->cache['mobilephone'];
  }
  public function isMember(){
    $this->loadDoctrineUser();
    if($this->doctrineUser == null){
      return false;
    }
    return $this->doctrineUser->isMember();
  }
  public function getLanguage(){
    if(!isset($this->lang)){
      $this->lang = $this->modx->runSnippet('getLanguage');
      if($this->lang == 'gb')
              $this->lang = 'en';
    }
    return $this->lang;
  }
  private function loadDoctrineUser(){
    $this->doctrineUser = $this->getUserRepository()->find($this->getId());
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