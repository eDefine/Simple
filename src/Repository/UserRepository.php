<?php

namespace Repository;

use Edefine\Framework\ORM\AbstractRepository;
use Form\ValueObject\LoginObject;

class UserRepository extends AbstractRepository
{
    public function loginUser(LoginObject $loginObject)
    {
        $results = $this->findAll(['email' => $loginObject->getEmail()]);

        if (count($results) != 1) {
            throw new \RuntimeException('Cannot login');
        }

        $user = $results[0];
        if ($user->getPassword() != md5(sprintf('%s_%s', $loginObject->getPlainPassword(), $user->getSalt()))) {
            throw new \RuntimeException('Cannot login');
        }

        return $user;
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