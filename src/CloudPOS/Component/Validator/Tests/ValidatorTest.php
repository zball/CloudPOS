<?php 
namespace CloudPOS\Component\Validator\Tests;

use CloudPOS\Component\Testing\TestCase;
use CloudPOS\Component\Validator\Validator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Mockery;

class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    protected $validator;
    
    public function setUp() {
        parent::setUp();
        
        $this->validator = new Validator($this->container->get('validator'));
    }
    
    public function testValidatorReturnsTrueOnSucces() 
    {
        $entity = new StdEntity;
        $entity->setNotEmpty('foo');
        
        $this->assertTrue($this->validator->validate($entity));
    }

    /**
     * @expectedException \Exception
     * @throws \Exception
     */
    public function testValidatorThrowsExceptionOnFaliure()
    {
        $this->assertContains('This value should not be blank.', $this->validator->validate(new StdEntity));
    }
}



/**
 * @ORM\Entity
 */
class StdEntity 
{
    /**
     * @Assert\NotBlank()
     */
    protected $notEmpty;
    
    public function setNotEmpty($value) {
        $this->notEmpty = $value;
        
        return $this;
    }
    
    public function getNotEmpty() {
        return $this->notEmpty;
    }
}