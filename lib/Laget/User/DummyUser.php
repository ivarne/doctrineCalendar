<?php
namespace Laget\User;

class DummyUser implements UserInterface{
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
  public function isMember(){
    return true;
  }
  public function getLanguage(){
    if(isset($_GET['lang']) && strlen($_GET['lang'])==2){
      return $_GET['lang'];
    }
    return 'no';
  }
}