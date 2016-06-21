<?php
namespace CloudPOS\Bundle\StoreBundle\Controller;

use CloudPOS\Bundle\StoreBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MenuController extends Controller
{
    /**
     * @Route("/menus")
     * @Method({"GET"})
     */
    public function listAction()
    {
        return $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Menu')->findAll();
    }

    /**
     * @Route("/menus/{id}")
     * @Method({"GET"})
     */
    public function getAction($id)
    {
        $menu = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Menu')->find($id);
        if (!$menu) {
            throw new NotFoundHttpException('The menu you queried for does not exist.');
        }

        return $menu;
    }

    /**
     * @Route("/menus")
     * @Method({"POST", "OPTIONS"})
     *
     * @param Request $request
     * @return Menu
     */
    public function postAction(Request $request)
    {
        $menu = new Menu();
        $menu->setName($request->get('name'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);;
        $em->flush();

        return $menu;
    }

    /**
     * @Route("/menus/{id}")
     * @Method({"PUT", "OPTIONS"})
     *
     * @param Request $request
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function putAction(Request $request, $id)
    {
        $menu = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Menu')->find($id);
        if ($menu === null) {
            throw new NotFoundHttpException('The menu you are trying to update does not exist');
        }

        $menu->setName($request->get('name', $menu->getName()));

        $em = $this->getDoctrine()->getManager();
        $em->persist($menu);
        $em->flush();
    }
}
