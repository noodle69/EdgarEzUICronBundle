<?php

namespace Edgar\EzUICronBundle;

use Edgar\EzUICronBundle\DependencyInjection\Security\PolicyProvider\UICronPolicyProvider;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EdgarEzUICronBundle.
 */
class EdgarEzUICronBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var EzPublishCoreExtension $eZExtension */
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new UICronPolicyProvider($this->getPath()));
    }
}
