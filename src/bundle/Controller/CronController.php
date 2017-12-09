<?php

namespace Edgar\EzUICronBundle\Controller;

use Edgar\EzUICronBundle\Service\EzCronService;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CronController extends Controller
{
    /** @var EzCronService $cronService cron service */
    protected $cronService;

    public function __construct(
        EzCronService $cronService
    ) {
        $this->cronService = $cronService;
    }

    public function menuAction(Request $request): Response
    {
        $crons = $this->cronService->getCrons();

        return $this->render('@EdgarEzUICron/cron/view.html.twig', [
            'crons' => $crons,
        ]);
    }
}
