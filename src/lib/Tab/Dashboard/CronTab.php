<?php

namespace Edgar\EzUICron\Tab\Dashboard;

use Edgar\CronBundle\Entity\EdgarCron;
use Edgar\EzUICronBundle\Service\EzCronService;
use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Repository;
use EzSystems\EzPlatformAdminUi\Tab\AbstractTab;
use EzSystems\EzPlatformAdminUi\Tab\OrderedTabInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;

class CronTab extends AbstractTab implements OrderedTabInterface
{
    /** @var EzCronService $cronService */
    protected $cronService;

    /** @var Repository $repository */
    protected $repository;

    /** @var PermissionResolver  */
    private $permissionResolver;

    /**
     * @param Environment $twig
     * @param TranslatorInterface $translator
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        Repository $repository,
        EzCronService $cronService,
        PermissionResolver $permissionResolver
    ) {
        parent::__construct($twig, $translator);

        $this->repository = $repository;
        $this->cronService = $cronService;
        $this->permissionResolver = $permissionResolver;
    }

    public function getIdentifier(): string
    {
        return 'cron';
    }

    public function getName(): string
    {
        return
            $this->translator->trans('tab.name.cron', [], 'dashboard');
    }

    public function getOrder(): int
    {
        return 300;
    }

    public function renderView(array $parameters): string
    {
        if (!$this->permissionAccess('cron', 'dashboard')) {
            return $this->twig->render('@EdgarEzUICron/dashboard/tab/cron.html.twig', [
                'crons' => [],
            ]);
        }

        $crons = $this->cronService->listCronsStatus();

        $cronRows = [];
        foreach ($crons as $cron) {
            $cronRows[] = [
                'alias' => $cron->getAlias(),
                'queued' => $cron instanceof EdgarCron
                    ? ($cron->getQueued() ? $cron->getQueued()->format('d-m-Y H:i') : false)
                    : false,
                'started' => $cron instanceof EdgarCron ? ($cron->getStarted()
                    ? $cron->getStarted()->format('d-m-Y H:i') : false)
                    : false,
                'ended' => $cron instanceof EdgarCron ? ($cron->getEnded()
                    ? $cron->getEnded()->format('d-m-Y H:i') : false)
                    : false,
                'status' => $cron instanceof EdgarCron ? $cron->getStatus() : false,
            ];
        }

        return $this->twig->render('@EdgarEzUICron/dashboard/tab/cron.html.twig', [
            'crons' => $cronRows,
        ]);
    }

    protected function permissionAccess(string $module, string $function): bool
    {
        if (!$this->permissionResolver->hasAccess($module, $function)) {
            return false;
        }

        return true;
    }
}
