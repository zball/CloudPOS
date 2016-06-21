<?php
/**
 * Created by PhpStorm.
 * User: zacball
 * Date: 1/23/16
 * Time: 5:17 PM
 */

namespace CloudPOS\Bundle\StoreBundle\Manager;

use CloudPOS\Bundle\StoreBundle\Entity\Manageable;
use Doctrine\ORM\EntityManager;
use CloudPOS\Component\Validator\Validator;

/**
 * Class BaseManager
 * @package CloudPOS\Bundle\StoreBundle\Manager
 */
abstract class BaseManager implements ManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Validator
     */
    protected $validator;

    protected $errors;

    /**
     * @param EntityManager $em
     * @param Validator $validator
     */
    public function __construct( EntityManager $em, Validator $validator )
    {
        $this->entityManager = $em;
        $this->validator = $validator;
    }

    /**
     * @param Manageable $entity
     */
    public function delete(Manageable $entity )
    {
        $this->entityManager->remove( $entity );
        $this->flush();
    }

    /**
     * @param Manageable $entity
     */
    public function save( Manageable $entity )
    {
        $this->entityManager->persist( $entity );
        $this->flush();
    }

    /**
     *
     */
    public function flush()
    {
        $this->entityManager->flush();
    }

    public function isValid(Manageable $entity)
    {
        if(true !== $this->errors = $this->validator->validate( $entity )) {
            return false;
        }

        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}