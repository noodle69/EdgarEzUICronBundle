<?php

namespace Edgar\EzUICron\Form\Validator\Constraint;

use Cron\CronExpression;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ExpressionConstraintValidator.
 */
class ExpressionConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!CronExpression::isValidExpression($value)) {
            $this->context->addViolation(
                $constraint->message,
                ['%string%' => $value]
            );
        }
    }
}
