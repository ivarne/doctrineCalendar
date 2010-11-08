<?php
namespace Entities\Repositories;

use Doctrine\ORM\EntityRepository;
use Entities;
use Doctrine\DBAL\LockMode;
/**
 * Description of RegistrationTaskRepository
 *
 * @author ivarne
 */
class RegistrationTaskRepository extends EntityRepository {
  /**
   *
   * @param int $id
   * @param <type> $lockMode
   * @return \Entities\RegistrationTask
   */
  public function find($id, $lockMode = LockMode::NONE, $lockVersion = null) {
    return parent::find($id, $lockMode,$lockVersion);
  }
}
?>
