<?php

namespace Edgar\EzUICron\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class ExpressionConstraint.
 */
class ExpressionConstraint extends Constraint
{
    /** @var string $message */
    public $message = 'The string "%string%" is not a valid expression.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return ExpressionConstraintValidator::class;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
