<?php

declare(strict_types=1);

namespace App\EventListener;

use App\DBAL\DatabaseConnectionWrapper;
use App\Entity\Main\Customer;
use App\Repository\CustomerRepository;
use App\Service\CustomerManager;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CurrentCustomerListener
{
    private $customerManager;

    private $container;

    private $baseHost;

    private $environment;

    public function __construct(
        CustomerManager $customerManager,
        ContainerInterface $container,
        string $baseHost,
        string $environment
    ) {
        $this->customerManager = $customerManager;
        $this->container       = $container;
        $this->baseHost        = $baseHost;
        $this->environment     = $environment;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $currentHost = $request->getHttpHost();
        $subdomain   = str_replace('.' . $this->baseHost, '', $currentHost);

        if ($subdomain === $this->baseHost) {
            return;
        }

        $currentCustomer = $this->getCurrentCustomer($subdomain);
        $currentCustomerArray = $currentCustomer->toArray();

        $request->request->set('current_customer', $currentCustomerArray);

        $this->customerManager->setCurrentCustomer($currentCustomerArray);

        $this->setConnection($subdomain);
    }

    private function getCurrentCustomer(string $subdomain): Customer
    {
        /** @var CustomerRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Customer::class, 'default');
        $customer   = $repository->findBySubdomain($subdomain);

        if (! $customer) {
            throw new NotFoundHttpException(
                sprintf('The customer \'%s\' cannot be found!', $subdomain)
            );
        }

        return $customer;
    }

    private function setConnection(string $subdomain)
    {
        $connection = $this->getDoctrine()->getConnection('customer');

        if (! $connection instanceof DatabaseConnectionWrapper) {
            throw new RuntimeException(
                'Current connection is invalid!'
            );
        }

        $connection->selectDatabase(sprintf('app_%s_%s', $subdomain, $this->environment));
    }

    protected function getDoctrine(): ManagerRegistry
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application. Try running "composer require symfony/orm-pack".');
        }

        return $this->container->get('doctrine');
    }
}
