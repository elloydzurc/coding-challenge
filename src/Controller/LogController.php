<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\Log\Interface\LogServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LogController extends AbstractController
{
    public function __construct(private readonly LogServiceInterface $analytics)
    {
    }

    #[Route('/count', name: 'count', methods: ['GET'])]
    public function count(Request $request): Response
    {
        try {
            $response = [
                'code' => Response::HTTP_OK,
                'output' => [
                    'count' => $this->analytics->filter($request->query->all()),
                ],
            ];
        } catch (\Exception $exception) {
            $response = [
                'code' => Response::HTTP_BAD_REQUEST,
                'output' => [
                    'error' => $exception->getMessage(),
                ],
            ];
        }

        return new JsonResponse($response['output'], $response['code']);
    }
}
