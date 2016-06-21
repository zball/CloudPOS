<?php
namespace CloudPOS\Bundle\UserBundle\Service;

use CloudPOS\Bundle\UserBundle\Doctrine\UserRepository;
use CloudPOS\Component\Validator\Tests\ValidatorTest;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var ObjectRepository
     */
    protected $userRepository;

    /**
     * UserProvider constructor.
     * @param ObjectRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username)
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->userRepository->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->userRepository->getClassName() === $class
        || is_subclass_of($class, $this->userRepository->getClassName());
    }
}