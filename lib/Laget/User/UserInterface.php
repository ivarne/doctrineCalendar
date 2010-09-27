<?php
namespace Laget\User;
interface UserInterface{
  public function hasPermission($permission);
  public function getId();
  public function isLoggedIn();
  public function getName();
  public function getEmail();
  public function getTelephone();
  public function isMember();
  public function getLanguage();
}