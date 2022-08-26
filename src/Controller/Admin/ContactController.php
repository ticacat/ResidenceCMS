<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\Type\ContactType;
use App\Form\Type\UserType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Service\Admin\ContactService;
use App\Service\Admin\UserService;
use App\Utils\UserFormDataSelector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends BaseController
{
    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function index(Request $request, ContactRepository $repository): Response
    {
        $contacts = $repository->findBy(['state' => ['unseen','pendent_response']],['id'=> 'desc']);

        return $this->render('admin/contact/index.html.twig', [
            'site' => $this->site($request),
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/admin/contact/new", name="admin_contact_new")
     */
    public function new(Request $request, ContactService $service): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $service->create($contact);

            /** @var ClickableInterface $button */
            $button = $form->get('saveAndCreateNew');
            if ($button->isClicked()) {
                return $this->redirectToRoute('admin_contact_new');
            }

            return $this->redirectToRoute('admin_contact');
        }

        return $this->render('admin/contact/new.html.twig', [
            'site' => $this->site($request),
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/admin/contact/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_contact_edit")
     */
    public function edit(Request $request, Contact $contact, ContactService $service): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->update($contact);
            return $this->redirectToRoute('admin_contact');
        }

        return $this->render('admin/contact/edit.html.twig', [
            'site' => $this->site($request),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes an User entity.
     *
     * @Route("/contact/{id<\d+>}/delete", methods={"POST"}, name="admin_contact_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Contact $contact, ContactService $service): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_contact');
        }

        $service->remove($contact);

        return $this->redirectToRoute('admin_contact');
    }
}
