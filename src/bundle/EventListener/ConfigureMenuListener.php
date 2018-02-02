<?php

namespace Edgar\EzUICronBundle\EventListener;

use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformAdminUi\Menu\MainMenuBuilder;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use JMS\TranslationBundle\Model\Message;

/**
 * Class ConfigureMenuListener.
 */
class ConfigureMenuListener implements TranslationContainerInterface
{
    const ITEM_CRONS = 'main__crons';

    /** @var PermissionResolver */
    private $permissionResolver;

    /**
     * ConfigureMenuListener constructor.
     *
     * @param PermissionResolver $permissionResolver
     */
    public function __construct(PermissionResolver $permissionResolver)
    {
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * Add cron to admin menu.
     *
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        if (!$this->permissionAccess('cron', 'list')) {
            return;
        }

        $menu = $event->getMenu();

        $cronsMenu = $menu->getChild(MainMenuBuilder::ITEM_ADMIN);
        $cronsMenu->addChild(self::ITEM_CRONS, ['route' => 'edgar.ezuicron.list']);
    }

    /**
     * Menu label translation.
     *
     * @return array
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_CRONS, 'messages'))->setDesc('Cronjobs'),
        ];
    }

    /**
     * Check if user can display cron menu.
     *
     * @param string $module
     * @param string $function
     *
     * @return bool
     */
    protected function permissionAccess(string $module, string $function): bool
    {
        if (!$this->permissionResolver->hasAccess($module, $function)) {
            return false;
        }

        return true;
    }
}
