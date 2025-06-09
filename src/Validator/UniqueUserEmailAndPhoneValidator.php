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

        if ($this->repository->findBy(['email'=> $value->getEmail()])) {
            $this->context->buildViolation('Email {{ email }} already exist')
                ->setParameter('{{ email }}', $value->getEmail())
                ->addViolation()
            ;
        }

        if ($this->repository->findBy(['phone'=> $value->getPhone()])) {
            $this->context->buildViolation('Phone {{ phone }} already exist')
                ->setParameter('{{ phone }}', $value->getPhone())
                ->addViolation()
            ;
        }
    }
}
