<?php

namespace Edgar\EzUICronBundle\Command;

use Cron\CronExpression;
use Edgar\EzUICronBundle\Service\EzCronService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('edgarez:crons:run')
            ->setDescription('Edgar eZ cron scheduler');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EzCronService $cronService */
        $cronService = $this->getContainer()->get(EzCronService::class);
        /** @var array $crons */
        $crons = $cronService->getCrons();

        $cronsDue = [];
        foreach ($crons as $cron) {
            $cronExpression = CronExpression::factory($cron['expression']);
            if ($cron['enabled'] && $cronExpression->isDue()) {
                $cronsDue[] = $cron;
            }
        }

        foreach ($cronsDue as $cron) {
            if (!$cronService->isQueued($cron['alias'])) {
                $cronService->addQueued($cron['alias']);
            }
        }

        $cronService->runQueued($input, $output, $this->getApplication());
    }
}
