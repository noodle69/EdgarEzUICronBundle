<?php

namespace Edgar\EzUICronBundle\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformAdminUi\Menu\MainMenuBuilder;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use JMS\TranslationBundle\Model\Message;

class ConfigureMenuListener implements TranslationContainerInterface
{
    const ITEM_CRONS = 'main__crons';

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $cronsMenu = $menu->getChild(MainMenuBuilder::ITEM_ADMIN);
        $cronsMenu->addChild(self::ITEM_CRONS, ['route' => 'edgar.ezuicron.menu']);
    }

    /**
     * @return array
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_CRONS, 'messages'))->setDesc('Cronjobs'),
        ];
    }
}
