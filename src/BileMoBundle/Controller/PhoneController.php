<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\Phone;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PhoneController extends Controller
{
    /**
     * @Route("/", name="bile_mo_accueil_page")
     */
    public function indexAction()
    {
        return $this->render('@BileMo/Default/accueil.html.twig');
    }

    /**
     * @Rest\Get("/api", name="bile_mo_homepage")
     *
     * @View()
     */
    public function listPhonesAction()
    {
        $phones = $this->getDoctrine()->getRepository('BileMoBundle:Phone')->findAll();
        return $phones;
    }

    /**
     * @Get(path="/api/phone/{id}", name="bile_mo_phone_view", requirements={"id"="\d+"})
     *
     * @View()
     */
    public function ViewPhoneAction(Phone $phone)
    {
        return $phone;
    }
}