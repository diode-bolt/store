<?php

namespace App\Service;

use App\Entity\Dto\User\UserListItem;
use App\Entity\Dto\User\UserRequest;
use App\Entity\Users\User;
use App\Query\Condition\Factory\ConditionFactory;
use App\Request\Dto\ListRequest;
use App\Response\Dto\UserListResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    use QueryListTrait, FilterConditionBuilder;
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private ConditionFactory $conditionFactory
    )
    {
    }

    public function getList(ListRequest $request): UserListResponse
    {
        $conditions = $this->buildFromFilters($request, User::class);

        $paginator = $this->getEntityList(
            User::class,
            UserListItem::class,
            $request->start,
            $request->limit,
            $conditions,
            $request->orderBy,
        );

        return new UserListResponse(count($paginator), iterator_to_array($paginator->getIterator()));
    }

    public function register(UserRequest $userDto): User
    {
        $user = new User();
        $user
            ->setEmail($userDto->email)
            ->setPhone($userDto->phone)
            ->setName($userDto->name)
            ->setPromoId($userDto->promoId)
            ->setPassword($this->hasher->hashPassword($user, $userDto->password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function changePass(User $user,#[\SensitiveParameter] string $pass): void
    {
        $user->setPassword($this->hasher->hashPassword($user, $pass));
        $this->em->persist($user);
        $this->em->flush();
    }
}