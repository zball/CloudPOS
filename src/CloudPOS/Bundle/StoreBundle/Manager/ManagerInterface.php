<?php
/**
 * Created by PhpStorm.
 * User: zacball
 * Date: 1/23/16
 * Time: 9:13 PM
 */

namespace CloudPOS\Bundle\StoreBundle\Manager;


use CloudPOS\Bundle\StoreBundle\Entity\Manageable;
use Symfony\Component\HttpFoundation\Request;

interface ManagerInterface
{

    public function save( Manageable $manageable );
    public function delete( Manageable $manageable );
    public function create( Request $request );
    public function update( Request $request, Manageable $manageable );

}