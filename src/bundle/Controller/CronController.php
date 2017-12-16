<?php

namespace Edgar\EzUICronBundle\Controller;

use Edgar\EzUICron\Form\SubmitHandler;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;
use Edgar\EzUICronBundle\Service\EzCronService;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\API\Repository\Repository;
use Edgar\EzUICron\Form\Factory\FormFactory;
use Symfony\Component\Translation\TranslatorInterface;

class CronController extends Controller
{
    /** @var EzCronSgetervice $cronService cron service */
    protected $cronService;

    /** @var Repository $repository */
    protected $repository;

    /** @var FormFactory  */
    protected $formFactory;

    /** @var SubmitHandler  */
    protected $submitHandler;

    /** @var NotificationHandlerInterface $notificationHandler */
    protected $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        EzCronService $cronService,
        Repository $repository,
        FormFactory $formFactory,
        SubmitHandler $submitHandler,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator
    ) {
        $this->cronService = $cronService;
        $this->repository = $repository;
        $this->formFactory = $formFactory;
        $this->submitHandler = $submitHandler;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
    }

    public function listAction(Request $request): Response
    {
        if ($this->repository->hasAccess('cron', 'list') !== true) {
            throw new UnauthorizedException('cron', 'list');
        }

        $crons = $this->cronService->getCrons();

        return $this->render('@EdgarEzUICron/cron/list.html.twig', [
            'crons' => $crons,
        ]);
    }

    public function updateAction(Request $request, $alias): Response
    {
        if ($this->repository->hasAccess('cron', 'update') !== true) {
            throw new UnauthorizedException('cron', 'update');
        }

        $cron = $this->cronService->getCron($alias);
        if (!$cron) {
            return new RedirectResponse($this->generateUrl('edgar.ezuicron.list', [
            ]));
        }

        $form = $this->formFactory->updateCron($cron);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, function (EdgarEzCron $data) {
                try {
                    $this->cronService->updateCron($data);
                } catch (NotFoundException $e) {
                    $this->notificationHandler->error(
                        $e->getMessage()
                    );

                    return $this->render('EdgarEzUICronBundle:cron:update.html.twig', [
                        'cron' => $cron,
                        'form_cron_update' => $form->createView(),
                    ]);
                }

                $this->notificationHandler->success(
                    $this->translator->trans(
                        'edgar.ezuicron.cron.updated',
                        [],
                        'edgarezuicron'
                    )
                );

                return new RedirectResponse($this->generateUrl('edgar.ezuicron.list', [
                ]));
            });

            if ($result instanceof Response) {
                return $result;
            }
        }

        return $this->render('EdgarEzUICronBundle:cron:update.html.twig', [
            'cron' => $cron,
            'form_cron_update' => $form->createView(),
        ]);
    }
}
