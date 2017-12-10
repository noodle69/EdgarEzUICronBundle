<?php

namespace Edgar\EzUICronBundle\Controller;

use Edgar\EzUICronBundle\Service\EzCronService;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $crons = $this->cronService->getCrons();

        return $this->render('@EdgarEzUICron/cron/view.html.twig', [
            'crons' => $crons,
        ]);
    }

    public function performAccessCheck()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($this->repository->hasAccess('cron', 'list') !== true) {
            throw new UnauthorizedException('cron', 'list');
        }
    }
}
