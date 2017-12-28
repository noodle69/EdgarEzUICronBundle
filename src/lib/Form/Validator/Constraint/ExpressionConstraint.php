<?php

namespace Edgar\EzUICron\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class ExpressionConstraint extends Constraint
{
    public $message = 'The string "%string%" is not a valid expression.';

    public function validatedBy(): string
    {
        return ExpressionConstraintValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
