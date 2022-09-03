<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ContactDto;
use App\Dto\FeedbackDto;
use App\Entity\Contact;
use App\Entity\Page;
use App\Form\Type\ContactType;
use App\Form\Type\FeedbackType;
use App\Message\SendFeedback;
use App\Repository\PageRepository;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends BaseController
{

   /**
     * @Route("/", methods={"GET|POST"}, name="homepage")
     */
   public function homepage(Request $request, MessageBusInterface $messageBus, PageRepository $pageRepository,  PropertyRepository $propertyRepository): Response
    {



        $slug = $request->attributes->get('slug');

        $page = $pageRepository->findOneBy(['locale' => $request->getLocale(), 'slug' => 'homepage'])
            ?? $pageRepository->findOneBy(['slug' => $slug]);

        $properties = $propertyRepository->findBy(
            [
                'available_now'=> 1,
                'show_slider_homepage' => 1
            ]
        );

        $lastproperties = $propertyRepository->findBy(
            [
                'available_now'=> 1,
            ],['id' =>'desc']
        );



        return $this->render('page/homepage.html.twig',
            [
                'lastproperties' => $lastproperties,
                'properties' => $properties,
                'site' => $this->site($request),
                'page' => $page,
            ]
        );
    }



    /**
     * @Route("/info/{slug}", methods={"GET|POST"}, name="page")
     */
    public function pageShow(Request $request, MessageBusInterface $messageBus, PageRepository $pageRepository): Response
    {

        $slug = $request->attributes->get('slug');
        $page = $pageRepository->findOneBy(['locale' => $request->getLocale(), 'slug' => $slug])
            ?? $pageRepository->findOneBy(['slug' => $slug]);

        if ($page->getAddContactForm() && '' !== $page->getContactEmailAddress()) {
            $feedback = new FeedbackDto();
            $feedback->setToEmail($page->getContactEmailAddress());

            $form = $this->createForm(FeedbackType::class, $feedback);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->saveFeedBack($feedback);
                $messageBus->dispatch(new SendFeedback($feedback));
                $this->addFlash('success', 'message.was_sent');

                return $this->redirectToRoute('page', ['slug' => $page->getSlug()]);
            }
        }

        return $this->render('page/show.html.twig',
            [
                'site' => $this->site($request),
                'page' => $page,
                'form' => (!empty($form) ? $form->createView() : []),
            ]
        );
    }


    function saveFeedBack(FeedbackDto $feedback){

        $contact = new Contact();



        $contact->setEmail($feedback->getFromEmail());
        $contact->setFullname($feedback->getFromName());
        $contact->setLegal(true);
        $contact->setTelefon('43543534');
        $contact->setContent(

            $feedback->getMessage());
        $em =$this->doctrine->getManager();
        $em->persist($contact);
        $em->flush();


    }
}
