<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\Phone;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Swagger\Annotations as SWG;

class PhoneController extends Controller
{
    /**
     * @Route("/", name="bile_mo_index")
     */
    public function indexAction()
    {
        return $this->render('@BileMo/Default/accueil.html.twig');
    }

    /**
     * View list of all phones
     *
     * Access to the API with Token generated from the ApiKey on the route => /api/token
     *
     * @Rest\Get("/api/phone", name="bile_mo_homepage")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="Bearer Token"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Display the list of phones",
     *     @SWG\Schema(
     *     )
     * )
     *
     * @SWG\Tag(name="Phone")
     *
     * @View()
     */
    public function listPhonesAction()
    {
        $phones = $this->getDoctrine()->getRepository('BileMoBundle:Phone')->findAll();

        return $phones;
    }

    /**
     * Viewing a phone detail
     *
     * Access to the API with Token generated from the ApiKey on the route => /api/token
     *
     * @Get(path="/api/phone/{id}", name="bile_mo_phone_view", requirements={"id"="\d+"})
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="Bearer Token"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Viewing the details of a phone",
     *     @SWG\Schema(
     *     )
     * )
     *
     * @SWG\Tag(name="Phone")
     *
     * @View()
     */
    public function ViewPhoneAction(Phone $phone)
    {
        return $phone;
    }
}