<?php

namespace Edgar\EzUICronBundle\Service;

use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller as BaseController;
use eZ\Publish\API\Repository\Repository;

class Controller extends BaseController
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

    public function performAccessCheck()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($this->repository->hasAccess('cron', 'list') !== true) {
            throw new UnauthorizedException('cron', 'list');
        }
    }
}
