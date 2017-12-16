<?php

namespace Edgar\EzUICron\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ArgumentsConstraintValidator extends ConstraintValidator
{
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
