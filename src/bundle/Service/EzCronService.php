<?php

namespace Edgar\EzUICronBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Edgar\Cron\Handler\CronHandler;
use Edgar\EzUICron\Repository\EdgarEzCronRepository;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use Edgar\Cron\Cron\CronInterface;
use Edgar\CronBundle\Entity\EdgarCron;
use Edgar\CronBundle\Service\CronService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\TranslatorInterface;

class EzCronService
{
    /** @var CronService $cronService cron service */
    protected $cronService;

    /** @var CronHandler $cronHandler */
    protected $cronHandler;

    /** @var EdgarEzCronRepository $repository */
    protected $repository;

    /** @var TranslatorInterface $translator */
    protected $translator;

    public function __construct(
        CronService $cronService,
        CronHandler $cronHandler,
        Registry $doctrineRegistry,
        TranslatorInterface $translator
    ) {
        $this->cronService = $cronService;
        $this->cronHandler = $cronHandler;
        $entityManager = $doctrineRegistry->getManager();
        $this->repository = $entityManager->getRepository(EdgarEzCron::class);
        $this->translator = $translator;
    }

    /**
     * Return cron status entries
     *
     * @return EdgarCron[] cron status entries
     */
    public function listCronsStatus(): array
    {
        return $this->cronService->listCronsStatus();
    }

    /**
     * @param string $alias
     * @param string $type
     * @param string $value
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function updateCron(string $alias, string $type, string $value)
    {
        $cron = $this->repository->find($alias);
        if (!$cron) {
            $crons = $this->getCrons();
            if (!isset($crons[$alias])) {
                throw new NotFoundException(
                    $this->translator->trans('cron alias not found', [], 'edgarezcron'),
                    'edgarezcron'
                );
            }

            $cron = new EdgarEzCron();
            $cron->setAlias($crons[$alias]['alias']);
            $cron->setExpression($crons[$alias]['expression']);
            $cron->setArguments($crons[$alias]['arguments']);
            $cron->setPriority($crons[$alias]['priority']);
            $cron->setEnabled($crons[$alias]['enabled']);
        }

        try {
            $this->repository->updateCron($cron, $type, $value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException(
                $type, $this->translator->trans('cron.invalid.type', ['%type%' => $type], 'edgarezcron')
            );
        }
    }

    /**
     * Return cron list detail
     *
     * @return array cron list
     */
    public function getCrons(): array
    {
        /** @var CronInterface[] $crons */
        $crons = $this->cronService->getCrons();
        /** @var EdgarEzCron[] $ezCrons */
        $ezCrons = $this->repository->listCrons();

        $return = [];
        foreach ($ezCrons as $ezCron) {
            $return[$ezCron->getAlias()] = [
                'alias' => $ezCron->getAlias(),
                'expression' => $ezCron->getExpression(),
                'arguments' => $ezCron->getArguments(),
                'priority' => (int)$ezCron->getPriority(),
                'enabled' => (int)$ezCron->getEnabled() == 1,
            ];
        }

        foreach ($crons as $cron) {
            if (!isset($return[$cron->getAlias()])) {
                $return[$cron->getAlias()] = [
                    'alias' => $cron->getAlias(),
                    'expression' => $cron->getExpression(),
                    'arguments' => $cron->getArguments(),
                    'priority' => (int)$cron->getPriority(),
                    'enabled' => true,
                ];
            }
        }

        return $return;
    }

    public function isQueued(string $alias): bool
    {
        return $this->cronService->isQueued($alias);
    }

    public function addQueued(string $alias)
    {
        $this->cronService->addQueued($alias);
    }

    public function runQueued(InputInterface $input, OutputInterface $output, Application $application)
    {
        /** @var EdgarCron[] $edgarCrons */
        $edgarCrons = $this->cronService->listQueued();
        /** @var CronInterface[] $crons */
        $crons = $this->cronHandler->getCrons();
        /** @var array $eZCrons */
        $eZCrons = $this->getCrons();

        $cronAlias = [];
        foreach ($crons as $cron) {
            if (isset($eZCrons[$cron->getAlias()])) {
                $cronAlias[$cron->getAlias()] = [
                    'cron' => $cron,
                    'arguments' => $eZCrons[$cron->getAlias()]['arguments'],
                    'priority' => $eZCrons[$cron->getAlias()]['priority'],
                ];
            }
        }

        /** @var EdgarCron[] $cronsToRun */
        $cronsToRun = [];
        if ($edgarCrons) {
            foreach ($edgarCrons as $edgarCron) {
                if (isset($cronAlias[$edgarCron->getAlias()])) {
                    $priority = $cronAlias[$edgarCron->getAlias()]['priority'];
                    if (!isset($cronsToRun[$priority])) {
                        $cronsToRun[$priority] = [];
                    }
                    $cronsToRun[$priority][] = $edgarCron;
                }
            }
        }

        ksort($cronsToRun);
        foreach ($cronsToRun as $priority => $edgarCrons) {
            foreach ($edgarCrons as $edgarCron) {
                $this->cronService->run($edgarCron);
                /** @var CronInterface $cron */
                $cron = $cronAlias[$edgarCron->getAlias()]['cron'];
                $cron->addArguments($cronAlias[$edgarCron->getAlias()]['arguments']);
                $cron->initApplication($application);
                $status = $cron->run($input, $output);
                $this->cronService->end($edgarCron, $status);
            }
        }
    }
}
