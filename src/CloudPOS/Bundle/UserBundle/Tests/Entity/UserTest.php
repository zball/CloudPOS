<?php 
namespace CloudPOS\Bundle\UserBundle\Tests\Entity;

use CloudPOS\Bundle\UserBundle\Entity\User;
use CloudPOS\Bundle\StoreBundle\Entity\Cart;

class UserTest extends \PHPUnit_Framework_TestCase 
{
    
    public function testAllPropertiesCanBeFilled() 
    {
        $user = (new User)
            ->setId(1)
            ->setUsername('bob')
            ->setEmail('bob@saggot.com')
            ->setPassword('testing')
            ->setCart( new Cart );
            
        $this->assertInstanceOf('CloudPOS\Bundle\UserBundle\Entity\User', $user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('bob', $user->getUsername());
        $this->assertEquals('bob@saggot.com', $user->getEmail());
        $this->assertEquals('testing', $user->getPassword());
        $this->assertInstanceOf('CloudPOS\Bundle\StoreBundle\Entity\Cart', $user->getCart() );
    }
}