<?php

namespace Repository;

use Edefine\Framework\ORM\AbstractRepository;
use Form\ValueObject\LoginObject;

class UserRepository extends AbstractRepository
{
    public function loginUser(LoginObject $loginObject)
    {
        $queryBuilder = $this->getSelectQueryBuilder()
            ->addWhere(sprintf('email = "%s"', $loginObject->getEmail()))
            ->addWhere(sprintf('password = MD5(CONCAT("%s_", salt))', $loginObject->getPlainPassword()));

        $query = $queryBuilder->getQuery();

        $results = $query->execute();

        if (count($results) != 1) {
            throw new \RuntimeException('Cannot login');
        }

        return $results[0];
    }

    public function loginUserBySessionId($sessionId)
    {
        $queryBuilder = $this->getSelectQueryBuilder()
            ->addWhere(sprintf('session = "%s"', $sessionId));

        $query = $queryBuilder->getQuery();

        $results = $query->execute();

        if (count($results) != 1) {
            throw new \RuntimeException('Cannot login');
        }

        return $results[0];
    }
}