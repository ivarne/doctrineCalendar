<?php
namespace Laget\Doctrine;


/**
 * Enkel logger med ekstra funksjonalitet i forhold til standard
 *
 * Legger til et ekstra felt som mangler alle felt som skal hentes fra databasen
 */
class DebugStack extends \Doctrine\DBAL\Logging\DebugStack{
      public function startQuery($sql, array $params = null, array $types = null)
    {
        if ($this->enabled) {
            $this->start = microtime(true);
            $from = explode('FROM', $sql,2);
            $this->queries[] = array(
              'from' => @$from[1],
              'sql' => $sql,
              'params' => $params,
              'types' => $types,
              'executionMS' => 0
              );
        }
    }
    /**
     * {@inheritdoc}
     */
    public function stopQuery($stmt = NULL)
    {
        $this->queries[(count($this->queries)-1)]['executionMS'] = microtime(true) - $this->start;
        if(is_object($stmt)){
          $this->queries[(count($this->queries)-1)]['rows'] = $stmt->rowCount();
        }
    }
}