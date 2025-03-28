<?php

namespace App\Security\Provider;

use App\Entity\Users\Admin;
use App\Repository\AdminRepository;
use Doctrine\Persistence\Proxy;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(private AdminRepository $repository)
    {
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Admin) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_debug_type($user)));
        }

        $refreshedUser = $this->repository->find($user->getId());

        if (null === $refreshedUser) {
            $e = new UserNotFoundException('User with id ' . $user->getId() . ' not found.');
            $e->setUserIdentifier($user->getId());

            throw $e;
        }

        if ($refreshedUser instanceof Proxy && !$refreshedUser->__isInitialized()) {
            $refreshedUser->__load();
        }

        return $refreshedUser;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class)
    {
        return $class === Admin::class;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->repository->findOneBy(['email' => $identifier]);

        if (null === $user) {
            $e = new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
            $e->setUserIdentifier($identifier);

            throw $e;
        }

        return $user;
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Admin) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_debug_type($user)));
        }

        $this->repository->upgradePassword($user, $newHashedPassword);
    }
}