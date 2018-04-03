<?php

namespace Edgar\EzUICronBundle\Controller;

use Edgar\EzUICron\Form\SubmitHandler;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;
use Edgar\EzUICronBundle\Service\EzCronService;
use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Publish\API\Repository\Repository;
use Edgar\EzUICron\Form\Factory\FormFactory;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CronController.
 */
class CronController extends Controller
{
    /** @var EzCronService $cronService cron service */
    protected $cronService;

    /** @var Repository $repository */
    protected $repository;

    /** @var FormFactory */
    protected $formFactory;

    /** @var SubmitHandler */
    protected $submitHandler;

    /** @var NotificationHandlerInterface $notificationHandler */
    protected $notificationHandler;

    /** @var TranslatorInterface */
    private $translator;

    /** @var PermissionResolver */
    private $permissionResolver;

    /**
     * CronController constructor.
     *
     * @param EzCronService $cronService
     * @param Repository $repository
     * @param FormFactory $formFactory
     * @param SubmitHandler $submitHandler
     * @param NotificationHandlerInterface $notificationHandler
     * @param TranslatorInterface $translator
     * @param PermissionResolver $permissionResolver
     */
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

    /**
     * List all crons.
     *
     * @return Response
     */
    public function listAction(): Response
    {
        $this->permissionAccess('uicron', 'list');

        $crons = $this->cronService->getCrons();

        return $this->render('@EdgarEzUICron/cron/list.html.twig', [
            'crons' => $crons,
        ]);
    }

    /**
     * Update cron informations.
     *
     * @param Request $request
     * @param $alias
     *
     * @return Response
     */
    public function updateAction(Request $request, $alias): Response
    {
        $this->permissionAccess('uicron', 'update');

        $cron = $this->cronService->getCron($alias);
        if (!$cron) {
            return new RedirectResponse($this->generateUrl('edgar.ezuicron.list', []));
        }

        $form = $this->formFactory->updateCron($cron);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->submitHandler->handle($form, $cron, function (EdgarEzCron $data, EdgarEzCron $cron, FormInterface $form) {
                if (!$this->cronService->updateCron($data)) {
                    $this->notificationHandler->error(
                        $this->translator->trans(
                            'edgar.ezuicron.cron.update.error',
                            [],
                            'edgarezuicron'
                        )
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

                return new RedirectResponse($this->generateUrl('edgar.ezuicron.list', []));
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

    /**
     * Check if user has permissions to access cron functions.
     *
     * @param string $module
     * @param string $function
     *
     * @return null|RedirectResponse
     */
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
