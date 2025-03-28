<?php

namespace App\Validator;

use App\Entity\Users\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UniqueUserEmailAndPhoneValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var UniqueUserEmailAndPhone $constraint */

        if (!($value instanceof User)) {
            throw new UnexpectedTypeException(get_debug_type($value), User::class);
        }

        if ($this->repository->checkUniqueUser($value)) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation()
        ;
    }
}
