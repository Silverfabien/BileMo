<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\Phone;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PhoneController extends Controller
{
    /**
     * @Rest\Get("/", name="bile_mo_homepage")
     *
     * @View()
     */
    public function listPhonesAction()
    {
        $phones = $this->getDoctrine()->getRepository('BileMoBundle:Phone')->findAll();
        return $phones;
    }

    /**
     * @Get(path="/phone/{slug}", name="bile_mo_phone_view", requirements={"id"="\d+"})
     *
     * @View()
     */
    public function ViewPhoneAction(Phone $phone)
    {
        return $phone;
    }
}
