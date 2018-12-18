<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\Client;
use BileMoBundle\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientController extends Controller
{

    /**
     * @Route("/api/client", name="bile_mo_list_client_page")
     */
    public function listClientAction(UserInterface $user)
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Vous devez être connecté pour acceder à cette page !");

        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository(Client::class)->findBy(['user' => $user]);

        return $this->render('@BileMo/Client/listMember.html.twig', ['listMembers' => $client]);
    }

    /**
     * @Route("/api/client/{id}", name="bile_mo_client_account_page")
     */
    public function clientAccountAction(UserInterface $user, Request $request, Client $client, $id)
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Vous devez être connecté pour acceder à cette page !");

        $em = $this->getDoctrine()->getManager();
        $userClient = $em->getRepository(Client::class)->findOneBy(['user' => $user, 'id' => $id]);

        if($userClient === null)
        {
            throw new NotFoundHttpException();
        }

        $deleteForm = $this->createDeleteForm($client);
        $deleteForm->handleRequest($request);

        if($deleteForm->isSubmitted() && $deleteForm->isValid())
        {
            $deleteClient = $this->getDoctrine()->getManager();
            $deleteClient->remove($client);
            $deleteClient->flush();

            return $this->redirectToRoute('bile_mo_accueil_page');
        }

        return $this->render('@BileMo/Client/accountUser.html.twig', ['accountUser' => $userClient, 'deleteClient' => $deleteForm->createView()]);
    }

    /**
     * @Route("/api/add_client", name="bile_mo_add_client_page")
     */
    public function addClientAction(UserInterface $user, Request $request)
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $client->setUser($user);
            var_dump($client);
            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('bile_mo_list_client_page');
        }

        return $this->render('@BileMo/Client/addClient.html.twig', ['addClient' => $form->createView()]);
    }

    public function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bile_mo_client_account_page', ['id' => $client->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}