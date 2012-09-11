<?php
namespace Laget\User;
interface UserInterface{
  public function __construct(\Doctrine\ORM\EntityManager $em);
  public function hasPermission($permission);
  public function getId();
  public function isLoggedIn();
  public function getName();
  public function getEmail();
  public function getTelephone();
  public function isMember(\DateTime $time = NULL);
  public function getLanguage();
  public function getDoctrineUser();
}