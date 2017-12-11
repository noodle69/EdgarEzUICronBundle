<?php

namespace Edgar\EzUICronBundle\Controller;

use Edgar\EzUICronBundle\Service\EzCronService;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\API\Repository\Repository;

class CronController extends Controller
{
    /** @var EzCronService $cronService cron service */
    protected $cronService;

    /** @var Repository $repository */
    protected $repository;

    public function __construct(
        EzCronService $cronService,
        Repository $repository
    ) {
        $this->cronService = $cronService;
        $this->repository = $repository;
    }

    public function menuAction(Request $request): Response
    {
        if ($this->repository->hasAccess('cron', 'list') !== true) {
            throw new UnauthorizedException('cron', 'list');
        }

        $crons = $this->cronService->getCrons();

        return $this->render('@EdgarEzUICron/cron/view.html.twig', [
            'crons' => $crons,
        ]);
    }
}
