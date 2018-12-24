<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\Client;
use BileMoBundle\Form\ClientType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientController extends FOSRestController
{
    /**
     * Viewing the list of Clients linked to a User
     *
     * Access to the API with Token generated from the ApiKey on the route => /api/token
     *
     * @Rest\Get("/api/client", name="bile_mo_client_list")
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
     *     description="Display of the Customer list linked to the User",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="jwtToken", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Client")
     *
     * @Rest\View()
     */
    public function listAction(UserInterface $user)
    {
        $repository = $this->getDoctrine()->getRepository(Client::class);
        $client = $repository->findBy(['user' => $user]);

        return $client;
    }

    /**
     * Viewing the details of a Client linked to the User
     *
     * Access to the API with Token generated from the ApiKey on the route => /api/token
     *
     * @Rest\Get("/api/client/{id}", name="bile_mo_client_show")
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
     *     description="Viewing Customer Details",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="jwtToken", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Client")
     *
     * @Rest\View()
     */
    public function showAction(UserInterface $user, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Client::class);
        $client = $repository->findOneBy([
            'user' => $user,
            'id' => $id
        ]);

        if($client === null)
        {
            throw new NotFoundHttpException('Erreur 404\r\n Une erreur est survenue, la page que vous avez demandé n\'existe pas');
        }

        return $client;
    }

    /**
     * Deletes a Client linked to the User
     *
     * Access to the API with Token generated from the ApiKey on the route => /api/token
     *
     * @Rest\Delete("/api/client/{id}", name="bile_mo_client_remove")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="Bearer Token"
     * )
     *
     * @SWG\Parameter(
     *     name="Deleting a Client",
     *     in="body",
     *     description="Deleting a Client linked to the User",
     *     @Model(type=ClientType::class)
     * )
     *
     * @SWG\Response(
     *     response=204,
     *     description="Deleting a Client linked to the User"
     * )
     *
     * @SWG\Tag(name="Client")
     * @Rest\View()
     */
    public function removeAction(Client $client)
    {
        $remove = $this->getDoctrine()->getManager();
        $remove->remove($client);
        $remove->flush();
    }

    /**
     * Adding a Client linked to the User
     *
     * Access to the API with Token generated from the ApiKey on the route => /api/token
     *
     * @Rest\Post("/api/client", name="bile_mo_client_add")
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="Bearer Token"
     * )
     *
     * @SWG\Parameter(
     *     name="Add a Customer",
     *     in="body",
     *     description="Add a Customer",
     *     @Model(type=ClientType::class)
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Adding the Client linked to the User",
     *     @SWG\Schema(
     *     ref=@Model(type=Client::class)
     *     )
     * )
     *
     * @SWG\Tag(name="Client")
     *
     * @Rest\View(StatusCode = 201)
     */
    public function addAction(Request $request, UserInterface $user)
    {
        $data = $this->get('jms_serializer')->deserialize($request->getContent(), 'array', 'json');
        $client = new Client();
        $form = $this->get('form.factory')->create(ClientType::class, $client);
        $form->submit($data);
        $client->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        return $this->view($client, Response::HTTP_CREATED, ['Location' => $this->generateUrl('bile_mo_client_show', ['id' => $client->getId()])]);
    }

    public function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bile_mo_client_show', ['id' => $client->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}