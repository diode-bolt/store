<?php

namespace App\Service;

use App\Entity\Users\User;
use App\Request\Dto\ListRequest;
use App\Response\Dto\UserListDto;
use App\Response\Dto\UserListResponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserListService
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function getList(ListRequest $request): UserListResponse
    {
        $query = $this->em
            ->createQuery('SELECT NEW ' . UserListDto::class .  '(u.id, u.email) FROM ' . User::class . ' u')
            ->setFirstResult($request->start);

        if ($request->limit > 0) {
            $query->setMaxResults($request->limit);
        }

        $paginator = new Paginator($query);

        return new UserListResponse(count($paginator), $paginator->getIterator());
    }
}