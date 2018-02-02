<?php

namespace Edgar\EzUICron\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ArgumentsConstraintValidator.
 */
class ArgumentsConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!empty($value) && preg_match_all('|[a-z0-9_\-]+:[a-z0-9_\-]+|', $value) === 0) {
            $this->context->addViolation(
                $constraint->message,
                ['%string%' => $value]
            );
        }
    }
}
