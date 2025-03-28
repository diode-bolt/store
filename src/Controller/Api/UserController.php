<?php

namespace App\Controller\Api;

use App\Entity\Users\User;
use App\Form\Users\UserType;
use App\Repository\UserRepository;
use App\Request\Dto\ListRequest;
use App\Response\Dto\UserListDto;
use App\Response\Dto\UserListResponse;
use App\Service\UserListService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Attribute\Groups;

#[Route('/api/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'api_user_index', methods: ['GET'])]
    #[Tag('users')]
    //#[\OpenApi\Attributes\Response(response:200,description: 'success',content: new JsonContent(ref: new Model(type: UserListResponse::class)))]
    public function index(#[MapRequestPayload] ListRequest $request, UserListService $listService): UserListResponse
    {
        return $listService->getList($request);
    }

    #[Route('/{id}', name: 'app_api_user_show', methods: ['GET'])]
    #[Tag('users')]
    #[Groups(['login'])]
    public function show(User $user): Response
    {
        return $this->render('api/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_api_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('api_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_api_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
