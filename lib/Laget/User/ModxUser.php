<?php
namespace Laget\User;

class ModxUser implements UserInterface{
  /**
   * @var DocumentParser
   */
  private $modx;
  private $cache;
  public function __construct(){
    global $modx;
    $this->modx = $modx;
  }
  public function hasPermission($permission){
    switch ($permission) {
      case 'intern_info':
        return $this->modx->isMemberOfWebGroup('programbehandling');
        break;
      case 'se upubliserte':
        return $this->modx->isMemberOfWebGroup('programbehandling');
        break;
      case 'redigere hendelser':
        return $this->modx->isMemberOfWebGroup('programbehandling');
        break;
      default:
        throw new \Exception('hasPermission kalt med ugyldig permission ('.$permission.')');
    }
  }
  public function getId(){
    return $this->modx->getLoginUserID();
  }
  public function isLoggedIn(){
    return $this->modx->userLoggedIn();
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
    throw new Exception('Not Implemented');
  }
  public function getLanguage(){
    if(!isset($this->lang)){
      $this->lang = $this->modx->runSnippet('getLanguage');
      if($this->lang == 'gb')
              $this->lang = 'en';
    }
    return $this->lang;
  }
}