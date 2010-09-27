<?php
namespace Laget\Routing;

interface RoutingInterface {
  public function newEvent();
  public function showEvent(\Entities\Event $event);
  public function editEvent(\Entities\Event $event);
  public function deleteEvent(\Entities\Event $event);
  public function monthView(\DateTime $date);
  public function saveEvent(\Entities\Event $event);
  public function publishEvent(\Entities\Event $event);
  public function searchForEvent();
}