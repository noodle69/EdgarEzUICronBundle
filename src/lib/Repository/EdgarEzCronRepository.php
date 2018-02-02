<?php

namespace Edgar\EzUICron\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;

/**
 * Class EdgarEzCronRepository.
 */
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

    /**
     * Get cron by alias.
     *
     * @param string $alias
     *
     * @return EdgarEzCron|null
     */
    public function getCron(string $alias): ?EdgarEzCron
    {
        return $this->findOneBy(['alias' => $alias]);
    }

    /**
     * Update cron object.
     *
     * @param EdgarEzCron $cron
     *
     * @return bool
     */
    public function updateCron(EdgarEzCron $cron): bool
    {
        try {
            $this->getEntityManager()->persist($cron);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            return false;
        }

        return true;
    }
}
