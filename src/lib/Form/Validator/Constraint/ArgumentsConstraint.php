<?php

namespace Edgar\EzUICron\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class ArgumentsConstraint extends Constraint
{
    public $message = 'The string "%string%" is not valid arguments.';

    public function validatedBy(): string
    {
        return ArgumentsConstraintValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
