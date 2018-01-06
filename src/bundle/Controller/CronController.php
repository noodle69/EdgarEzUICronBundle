<?php

namespace Edgar\EzUICronBundle\Controller;

use Edgar\EzUICron\Form\SubmitHandler;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;
use Edgar\EzUICronBundle\Service\EzCronService;
use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\API\Repository\Repository;
use Edgar\EzUICron\Form\Factory\FormFactory;
use Symfony\Component\Translation\TranslatorInterface;

class CronController extends Controller
{
    /** @var EzCronService $cronService cron service */
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

    /** @var PermissionResolver  */
    private $permissionResolver;

    public function __construct(
        EzCronService $cronService,
        Repository $repository,
        FormFactory $formFactory,
        SubmitHandler $submitHandler,
        NotificationHandlerInterface $notificationHandler,
        TranslatorInterface $translator,
        PermissionResolver $permissionResolver
    ) {
        $this->cronService = $cronService;
        $this->repository = $repository;
        $this->formFactory = $formFactory;
        $this->submitHandler = $submitHandler;
        $this->notificationHandler = $notificationHandler;
        $this->translator = $translator;
        $this->permissionResolver = $permissionResolver;
    }

    public function listAction(): Response
    {
        $this->permissionAccess('cron', 'list');

        $crons = $this->cronService->getCrons();

        return $this->render('@EdgarEzUICron/cron/list.html.twig', [
            'crons' => $crons,
        ]);
    }

    public function updateAction(Request $request, $alias): Response
    {
        $this->permissionAccess('cron', 'update');

        $cron = $this->cronService->getCron($alias);
        if (!$cron) {
            return new RedirectResponse($this->generateUrl('edgar.ezuicron.list', [
            ]));
        }

        $form = $this->formFactory->updateCron($cron);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, $cron, function (EdgarEzCron $data, EdgarEzCron $cron, FormInterface $form) {
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

    protected function permissionAccess(string $module, string $function): ?RedirectResponse
    {
        if (!$this->permissionResolver->hasAccess($module, $function)) {
            $this->notificationHandler->error(
                $this->translator->trans(
                    'edgar.ezuicron.permission.failed',
                    [],
                    'edgarezuicron'
                )
            );
            return new RedirectResponse($this->generateUrl('ezplatform.dashboard', []));
        }

        return null;
    }
}
