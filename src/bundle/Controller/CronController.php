<?php

namespace Edgar\EzUICronBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CronController extends Controller
{
    public function menuAction(Request $request): Response
    {
        $crons = $this->cronService->getCrons();

        return $this->render('@EdgarEzUICron/cron/view.html.twig', [
            'crons' => $crons,
        ]);
    }
}
