<?php

namespace Edgar\EzUICron\Form\Type;

use Edgar\EzUICron\Form\Validator\Constraint\ArgumentsConstraint;
use Edgar\EzUICron\Form\Validator\Constraint\ExpressionConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as PasswordFieldType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CronType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('expression', TextType::class, [
                'required' => true,
                'constraints' => [new ExpressionConstraint()],
            ])
            ->add('arguments', TextType::class, [
                'required' => false,
                'constraints' => [new ArgumentsConstraint()],
            ])
            ->add('priority', IntegerType::class, [
                'required' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('update', SubmitType::class, [
                'label' => /** @Desc("Update") */
                    'cron_update_form.update',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
            ]);
    }
}
