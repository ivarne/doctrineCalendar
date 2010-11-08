<?php
namespace Laget\Routing;

interface RoutingInterface {
  public function newEvent();
  public function showEvent(\Entities\Event $event);
  public function editEvent(\Entities\Event $event);
  public function deleteEvent(\Entities\Event $event);
  public function monthView(\DateTime $date);
  public function saveEvent();
  public function publishEvent(\Entities\Event $event);
  public function searchForEvent();
  public function JSONevents();
  public function showSpeaker(\Entities\Speaker $speaker);
  public function listSpeakers();
  public function editSpeaker(\Entities\Speaker $speaker);
  public function saveSpeaker(\Entities\Speaker $speaker);
  public function login();
  public function logout();
  public function saveRegistration(\Entities\Event $event);
}