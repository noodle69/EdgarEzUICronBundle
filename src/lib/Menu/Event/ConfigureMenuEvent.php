<?php

namespace Edgar\EzUICron\Menu\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

/**
 * Class ConfigureMenuEvent.
 */
class ConfigureMenuEvent
{
    const CRON_EDIT_SIDEBAR_RIGHT = 'edgar.ezuicron.menu_configure.cron_edit_sidebar_right';

    /** @var FactoryInterface */
    private $factory;

    /** @var ItemInterface */
    private $menu;

    /** @var array|null */
    private $options;

    /**
     * ConfigureMenuEvent constructor.
     *
     * @param FactoryInterface $factory
     * @param ItemInterface $menu
     * @param array $options
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu, array $options = [])
    {
        $this->factory = $factory;
        $this->menu = $menu;
        $this->options = $options;
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }

    /**
     * @return ItemInterface
     */
    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }
}
