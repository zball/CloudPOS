<?php
namespace CloudPOS\Bundle\UserBundle\Controller;

use CloudPOS\Bundle\UserBundle\Service\UserService;
use Doctrine\Common\Collections\Criteria;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $service;

    /**
     * @Route("/users")
     * @QueryParam(name="username", requirements="[a-zA-Z0-9]+", description="Users filtered by username")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $criteria = new Criteria();
        foreach ($paramFetcher->all() as $name => $value) {

            $criteria->where($criteria->expr()->contains($name, $value));
        }

        return $this->getDoctrine()->getRepository('CloudPOSUserBundle:User')->matching($criteria)->toArray();
    }

    /**
     * @Route("/users/{id}")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function getAction($id)
    {
        return $this->getDoctrine()->getRepository('CloudPOSUserBundle:User')->find($id);
    }

    /**
     * @Route("/users")
     * @Method({"POST", "OPTIONS"})
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $manager = $this->get('cloud_pos_user.doctrine.user_manager');

        $user = $manager->createUser($request);

        $manager->save($user);

        return $user;
    }

    /**
     * @Route("/users/{id}")
     * @Method({"PUT", "OPTIONS"})
     *
     * @param Request $request
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function putAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('CloudPOSUserBundle:User')->find($id);
        if ($user === null) {
            throw new NotFoundHttpException('The user you are trying to update does not exist');
        }

        $user->setUsername($request->get('username', $user->getUsername()));
        $user->setEmail($request->get('email', $user->getEmail()));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}
