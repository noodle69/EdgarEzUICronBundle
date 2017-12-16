<?php

namespace Edgar\EzUICron\Repository;

use Cron\CronExpression;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;

class EdgarEzCronRepository extends EntityRepository
{
    /**
     * List ez cron entries
     *
     * @return EdgarEzCron[]
     */
    public function listCrons(): array
    {
        return $this->findAll();
    }

    public function getCron(string $alias): ?EdgarEzCron
    {
        return $this->findOneBy(['alias' => $alias]);
    }

    public function updateCron(EdgarEzCron $cron)
    {
        $this->getEntityManager()->persist($cron);
        $this->getEntityManager()->flush();
    }
}
