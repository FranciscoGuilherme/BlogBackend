<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

use App\Entity\User;
use App\Helpers\ResponseHelper;

/**
 * @Route("/api/v1")
 */
class CreateUserController extends AbstractController
{
    private ResponseHelper $responseHelper;

    private LoggerInterface $loggerInterface;

    private EntityManagerInterface $entityManager;

    public function __construct(
        ResponseHelper $responseHelper,
        LoggerInterface $loggerInterface,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->responseHelper = $responseHelper;
        $this->loggerInterface = $loggerInterface;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @Route("/users/create", methods={"POST"}, name="register")
     */
    public function action(Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $requestContent = $request->toArray();

        $username = $requestContent['_username'];
        $password = $requestContent['_password'];

        try {
            $user = new User($username);
            $pass = $encoder->encodePassword($user, $password);
            $user->setPassword($pass);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();

            $response = $this->responseHelper->mount(
                Response::HTTP_OK,
                ResponseHelper::USER_NEW_USER_MESSAGE,
                ['id' => '$user->getId()']
            );
        }
        catch (\Exception $e) {
            $this->loggerInterface->error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTrace()
            ]);

            $response = $this->responseHelper->mount(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ResponseHelper::DB_SAVING_ERROR_MESSAGE
            );
        }

        return new JsonResponse($response['payload'], $response['status']);
    }
}
