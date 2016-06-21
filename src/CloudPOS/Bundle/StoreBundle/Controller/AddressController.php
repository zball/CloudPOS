<?php
namespace CloudPOS\Bundle\StoreBundle\Controller;

use CloudPOS\Bundle\StoreBundle\Entity\Address;
use CloudPOS\Bundle\StoreBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class AddressController extends Controller
{
    /**
     * @Route("/users/{id}/addresses")
     * @Method({"GET"})
     */
    public function listAction($id)
    {
        return $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Address')->findBy(['user' => $id]);
    }

    /**
     * @Route("/users/{user}/addresses/{id}")
     * @Method({"GET"})
     */
    public function getAction($user, $id)
    {
        $address = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Address')->findOneBy([
            'user' => $user,
            'id' => $id,
        ]);
        if (!$address) {
            throw new NotFoundHttpException('The menu you queried for does not exist.');
        }

        return $address;
    }

    /**
     * @Route("/users/{id}/addresses")
     * @Method({"POST", "OPTIONS"})
     *
     * @param Request $request
     * @return Menu
     */
    public function postAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('CloudPOSUserBundle:User')->findOneBy(['id' => $id]);
        if (!$user) {
            throw new NotFoundHttpException('The user you are trying to provide an address for could not be found.');
        }

        $address = (new Address())
            ->setUser($user)
            ->setName($request->get('name'))
            ->setState($request->get('state'))
            ->setCity($request->get('city'))
            ->setAddress($request->get('address'))
            ->setPostalCode($request->get('postal_code'))
            ->setCountryCode($request->get('country_code'))
            ->setDefault($request->get('default'));

        $em = $this->getDoctrine()->getManager();

        if ($address->isDefault()) {
            // Mark all other addresses to default 0
            foreach ($this->getDoctrine()->getRepository('CloudPOSStoreBundle:Address')->findAll() as $row) {
                $row->setDefault(false);
                $em->persist($row);
            }
        }

        $em->persist($address);
        $em->flush();

        return $address;
    }

    /**
     * @Route("/users/{user}/addresses/{id}")
     * @Method({"PUT", "OPTIONS"})
     *
     * @param Request $request
     * @param $user
     * @param $id
     * @return Response
     */
    public function putAction(Request $request, $user, $id)
    {
        $address = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Address')->findOneBy([
            'user' => $user,
            'id' => $id,
        ]);

        if (!$address) {
            throw new NotFoundHttpException('The menu you queried for does not exist.');
        }

        $address->setName($request->get('name'))
            ->setState($request->get('state'))
            ->setCity($request->get('city'))
            ->setAddress($request->get('address'))
            ->setPostalCode($request->get('postal_code'))
            ->setCountryCode($request->get('country_code'))
            ->setDefault($request->get('default'));

        $em = $this->getDoctrine()->getManager();

        if ($address->isDefault()) {
            // Mark all other addresses to default 0
            foreach ($this->getDoctrine()->getRepository('CloudPOSStoreBundle:Address')->findAll() as $row) {
                if($row->getId() != $address->getId()) {
                    $row->setDefault(false);
                    $em->persist($row);
                }
            }
        }

        $em->persist($address);
        $em->flush();
    }

    /**
     * @Route("/users/{user}/addresses/{id}")
     * @Method({"DELETE", "OPTIONS"})
     *
     * @param $user
     * @param $id
     * @return Response
     */
    public function deleteAction($user, $id) {
        $address = $this->getDoctrine()->getRepository('CloudPOSStoreBundle:Address')->findOneBy([
            'user' => $user,
            'id' => $id,
        ]);

        if (!$address) {
            throw new NotFoundHttpException('The menu you queried for does not exist.');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();
    }
}
