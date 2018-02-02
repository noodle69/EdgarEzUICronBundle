<?php

namespace Edgar\EzUICron\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class ArgumentsConstraint.
 */
class ArgumentsConstraint extends Constraint
{
    /** @var string $message */
    public $message = 'The string "%string%" is not valid arguments.';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return ArgumentsConstraintValidator::class;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
