<?php

namespace App\Validator;

use App\Repository\AdminRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class AdminUniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private AdminRepository $repository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var AdminUniqueEmail $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!$this->repository->findOneBy(['email'=> $value])) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation()
        ;
    }
}
