<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\Log\Interface\LogAnalyticsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LogController extends AbstractController
{
    public function __construct(private readonly LogAnalyticsInterface $analytics)
    {
    }

    #[Route('/count', name: 'count', methods: ['GET'])]
    public function count(Request $request): Response
    {
        return new JsonResponse([
            'count' => $this->analytics->filter($request->query->all()),
        ]);
    }
}
