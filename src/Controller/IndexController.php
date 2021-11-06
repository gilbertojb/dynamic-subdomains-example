<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    public function index(Request $request): JsonResponse
    {
        $currentCustomer = $request->request->get('current_customer');

        if (! is_null($currentCustomer)) {
            return $this->json(sprintf(
                'Welcome to %s home page', $currentCustomer['name']
            ));
        }

        return $this->json('Hello Message');
    }
}
