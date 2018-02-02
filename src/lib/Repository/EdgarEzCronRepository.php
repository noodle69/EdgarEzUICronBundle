<?php

namespace Edgar\EzUICron\Repository;

use Doctrine\ORM\EntityRepository;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;

class EdgarEzCronRepository extends EntityRepository
{
    /**
     * List ez cron entries.
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
