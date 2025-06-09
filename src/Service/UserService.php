<?php

namespace App\Service;

use App\Entity\Dto\UserListItem;
use App\Entity\Dto\UserRequest;
use App\Entity\Users\User;
use App\Request\Dto\ListRequest;
use App\Response\Dto\UserListResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
    )
    {
    }

    public function getList(ListRequest $request): UserListResponse
    {
        $query = $this->em
            ->createQuery('SELECT NEW ' . UserListItem::class .  '(u.id, u.email) FROM ' . User::class . ' u')
            ->setFirstResult($request->start);

        if ($request->limit > 0) {
            $query->setMaxResults($request->limit);
        }

        $paginator = new Paginator($query);

        return new UserListResponse(count($paginator), $paginator->getIterator());
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

    public function changePass(User $user,#[\SensitiveParameter] string $pass)
    {
        $user->setPassword($this->hasher->hashPassword($user, $pass));
        $this->em->persist($user);
        $this->em->flush();
    }
}