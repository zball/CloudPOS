<?php
namespace CloudPOS\Bundle\UserBundle\Doctrine;

use CloudPOS\Bundle\UserBundle\Entity\User;
use CloudPOS\Bundle\UserBundle\Service\UserService;
use CloudPOS\Component\Validator\Validator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class UserManager extends UserService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param BCryptPasswordEncoder $encoder
     * @param Validator $validator
     * @param EntityManager $em
     */
    public function __construct(BCryptPasswordEncoder $encoder, Validator $validator, EntityManager $em)
    {
        parent::__construct($encoder, $validator);

        $this->em = $em;
    }

    public function save(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}