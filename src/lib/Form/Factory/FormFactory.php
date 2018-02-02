<?php

namespace Edgar\EzUICron\Form\Factory;

use Edgar\EzUICron\Form\Type\CronType;
use Edgar\EzUICronBundle\Entity\EdgarEzCron;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormFactory.
 */
class FormFactory
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /**
     * FormFactory constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Cron form interface.
     *
     * @param EdgarEzCron $data
     * @param null|string $name
     *
     * @return null|FormInterface
     */
    public function updateCron(
        EdgarEzCron $data,
        ?string $name = null
    ): ?FormInterface {
        $name = $name ?: sprintf('update-cron-%s', $data->getAlias());

        return $this->formFactory->createNamed(
            $name,
            CronType::class,
            $data,
            [
                'method' => Request::METHOD_POST,
                'csrf_protection' => true,
            ]
        );
    }
}
