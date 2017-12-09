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
        $query = $this->createQueryBuilder('c')
            ->getQuery();
        /** @var EdgarEzCron[] */
        return $query->getResult();
    }

    /**
     * Edit cron definition
     *
     * @param EdgarEzCron $cron cron object
     * @param string      $type cron property identifier
     * @param string      $value cron property value
     * @throws InvalidArgumentException|OptimisticLockException
     */
    public function updateCron(EdgarEzCron $cron, $type, $value)
    {
        switch ($type) {
            case 'expression':
                if (!CronExpression::isValidExpression($value)) {
                    throw new InvalidArgumentException(
                        'expression', 'cron.invalid.type'
                    );
                }
                $cron->setExpression($value);
                break;
            case 'arguments':
                if (preg_match_all('|[a-z0-9_\-]+:[a-z0-9_\-]+|', $value) === 0) {
                    throw new InvalidArgumentException(
                        'arguments', 'cron.invalid.type'
                    );
                }
                $cron->setArguments($value);
                break;
            case 'priority':
                if (!ctype_digit($value)) {
                    throw new InvalidArgumentException(
                        'priority', 'cron.invalid.type'
                    );
                }
                $cron->setPriority((int)$value);
                break;
            case 'enabled':
                if (!ctype_digit($value) && ((int)$value != 1 || (int)$value != 0)) {
                    throw new InvalidArgumentException(
                        'enabled', 'cron.invalid.type'
                    );
                }
                $cron->setEnabled((int)$value);
                break;
        }
        $this->getEntityManager()->persist($cron);
        $this->getEntityManager()->flush();
    }
}
