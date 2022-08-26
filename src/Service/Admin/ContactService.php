<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\Contact;
use App\Service\AbstractService;
use App\Transformer\UserTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class ContactService extends AbstractService
{
    private EntityManagerInterface $em;

    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($tokenManager, $requestStack);
        $this->em = $entityManager;
    }

    public function create(Contact $contact): void
    {
        $this->save($contact);
        $this->addFlash('success', 'message.created');
    }

    public function update(Contact $contact): void
    {
        $this->save($contact);
        $this->addFlash('success', 'message.updated');
    }

    public function remove(Contact $contact): void
    {
        $this->em->remove($contact);
        $this->em->flush();
        $this->addFlash('success', 'message.deleted');
    }

    private function save(Contact $contact): void
    {
        $this->em->persist($contact);
        $this->em->flush();
    }
}
