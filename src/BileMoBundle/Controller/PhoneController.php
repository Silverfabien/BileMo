<?php

namespace BileMoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PhoneController extends Controller
{
    /**
     * @Route("/", name="bile_mo_homepage")
     */
    public function indexAction()
    {
        return $this->render('@BileMo/Default/index.html.twig');
    }
}
