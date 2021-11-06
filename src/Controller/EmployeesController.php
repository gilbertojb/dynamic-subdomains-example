<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeesController extends AbstractController
{
    public function index(Request $request): JsonResponse
    {
        $currentCustomer = $request->request->get('current_customer');

        if (is_null($currentCustomer)) {
            return $this->json('Not found', Response::HTTP_NOT_FOUND);
        }

        $repository = $this->getDoctrine()->getRepository(Employee::class, 'customer');
        $employees  = $repository->findAll();

        return $this->json($employees);
    }
}
