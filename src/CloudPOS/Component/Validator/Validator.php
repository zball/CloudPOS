<?php

namespace CloudPOS\Component\Validator;

use Exception;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class Validator
{
    /**
     * @var $validator RecursiveValidator
     */
    public function __construct(RecursiveValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates an entity and returns errors on failure, true on success
     *
     * @var $entity
     */
    public function validate($entity)
    {
        $errors = $this->validator->validate($entity);

        if (count($errors) > 0) {
            $message = '';
            foreach ($errors as $key => $error) {
                $property = (property_exists($error->getConstraint(), 'fields')) ? $error->getConstraint()->fields : 'Unkown';
                $message .= sprintf('%s: %s|', $property, $error->getMessage());
            }

            throw new Exception(rtrim($message, '|'));
        }

        return true;
    }
}