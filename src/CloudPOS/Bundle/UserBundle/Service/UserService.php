<?php
namespace CloudPOS\Bundle\UserBundle\Service;

use CloudPOS\Bundle\UserBundle\Entity\User;
use CloudPOS\Component\Validator\Validator;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class UserService
{
    /**
     * @var BCryptPasswordEncoder
     */
    protected $encoder;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param BCryptPasswordEncoder $encoder
     * @param Validator $validator
     */
    public function __construct(BCryptPasswordEncoder $encoder, Validator $validator)
    {
        $this->encoder = $encoder;
        $this->validator = $validator;
    }

    public function prePersist(LifecycleEventArgs $args) {

    }

    /**
     * Creates a user based off the request and encrypts password
     *
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        $user = new User();
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPassword(
            $this->encoder->encodePassword($request->get('password'), $user->getSalt())
        );

        if(true !== $errors = $this->validator->validate($user)) {
            return $errors;
        }

        return $user;
    }
}