<?php
namespace Entities\Repositories;

use Doctrine\ORM\EntityRepository;
use Entities;

/**
 * SpeakerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SpeakerRepository extends EntityRepository
{
  public function getMostActiveSpeakers($limit = 10, $offset = 0){
    $q = $this->getEntityManager()
            ->createQuery('SELECT s , (SELECT COUNT(a) FROM \Entities\Event a WHERE a.speaker = s) as num_events, (SELECT MAX(b.start) FROM \Entities\Event b WHERE b.speaker = s) as sort FROM \Entities\Speaker s ORDER BY num_events DESC , sort DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);
    $speakers = $q->getResult();
    $result = array();
    foreach ($speakers as $speaker) {
      $speaker[0]->setNumEvents($speaker['num_events']);
      $result[]= $speaker[0];
    }
    return $result;
  }

  public function findJoinEvents($id){
    return $this->createQueryBuilder('s')
            ->leftJoin('s.events', 'e')
            ->where('s.id = :id')
            ->setParameter('id', $id,'integer')
            ->getQuery()
            ->getSingleResult();
  }
  public function findAll(){
    return $this->createQueryBuilder('s')
            ->orderBy('s.name')
            ->getQuery()
            ->getResult();
  }
}