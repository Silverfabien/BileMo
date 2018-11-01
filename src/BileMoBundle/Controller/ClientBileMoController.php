<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\Client;
use BileMoBundle\Entity\User;
use BileMoBundle\Form\ClientBileMoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClientBileMoController extends Controller
{

    /**
     * @Route("/client_bile_mo", name="bile_mo_list_client_page")
     */
    public function listClientAction()
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Vous devez être connecté pour acceder à cette page !");

        $user = $this->getDoctrine()->getManager()->getRepository(Client::class);
        $listMembers = $user->findAll();

        return $this->render('@BileMo/Client/listMember.html.twig', ['listMembers' => $listMembers]);
    }

    /**
     * @Route("/client_bile_mo/{username}", name="bile_mo_client_account_page")
     *
     * @ParamConverter("username", class="BileMoBundle\Entity\User", options={"mapping": {"username": "username"}})
     */
    public function clientAccountAction(User $username)
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Vous devez être connecté pour acceder à cette page !");

        $accountUser = $this->getDoctrine()->getRepository(Client::class)->findOneBy(['user' => $username]);

        return $this->render('@BileMo/Client/accountUser.html.twig', ['accountUser' => $accountUser]);
    }

    /**
     * @Route("/add_client_bile_mo", name="bile_mo_add_client_page")
     */
    public function addClientAction(Request $request)
    {
        $form = $this->createForm(ClientBileMoType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $addClient = $this->getDoctrine()->getManager();
            $addClient->persist($form);
            $addClient->flush();

            return $this->redirectToRoute('bile_mo_list_client_page');
        }

        return $this->render('@BileMo/Client/addClient.html.twig', ['addClient' => $form->createView()]);
    }
}