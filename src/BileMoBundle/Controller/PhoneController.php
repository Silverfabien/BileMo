<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\Phone;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PhoneController extends Controller
{
    /**
     * @Route("/", name="bile_mo_homepage")
     */
    public function indexAction()
    {
        $phones = $this->getDoctrine()->getManager()->getRepository('BileMoBundle:Phone')->findAll();

        return $this->render('@BileMo/Default/index.html.twig', ['phones' => $phones]);
    }

    /**
     * @Route("/{slug}", name="bile_mo_phone_view")
     */
    public function phoneViewAction(Phone $phone)
    {
        return $this->render('@BileMo/Default/view.html.twig', ['phone' => $phone]);
    }
}
