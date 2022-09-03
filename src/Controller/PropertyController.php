<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ContactDto;
use App\Dto\FeedbackDto;
use App\Entity\Contact;
use App\Entity\Property;
use App\Repository\FilterRepository;
use App\Repository\PropertyRepository;
use App\Repository\SimilarRepository;
use App\Service\URLService;
use App\Transformer\RequestToArrayTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends BaseController
{
    /**
     * @Route("/search", defaults={"page": "1"}, methods={"GET"}, name="property")
     * @Route("/premium", defaults={"page": "1"}, methods={"GET"}, name="property_premium")
     */
    public function search(Request $request, FilterRepository $repository, RequestToArrayTransformer $transformer): Response
    {


        $searchParams = $transformer->transform($request);
        $searchParams['level'] = $this->getUser()? $this->getUser()->getLevel(): 0;

        if($request->attributes->get('_route') == 'property_premium')
        {

            $properties = $repository->findByFilterWithPremium($searchParams,true);

            return $this->render('property/premium.html.twig',
                [
                    'site' => $this->site($request),
                    'properties' => $properties,
                    'searchParams' => $searchParams
                ]
            );
        }else{

            $properties = $repository->findByFilterWithPremium($searchParams);

            return $this->render('property/index.html.twig',
                [
                    'site' => $this->site($request),
                    'properties' => $properties,
                    'searchParams' => $searchParams,
                ]
            );

        }



    }

    /**
     * @Route("/map", methods={"GET"}, name="map_view")
     */
    public function mapView(Request $request, PropertyRepository $repository): Response
    {
        return $this->render('property/gmap.html.twig',
            [
                'site' => $this->site($request),
                'properties' => $repository->findAllPublished(),
            ]
        );
    }

    /**
     * @Route("/{citySlug}/{slug}/{id<\d+>}", methods={"GET|POST"}, name="property_show")
     * @IsGranted("PROPERTY_VIEW", subject="property", message="Properties can only be shown to their owners.")
     */
    public function propertyShow(Request $request, URLService $url, Property $property, SimilarRepository $repository): Response
    {
        if (!$url->isCanonical($property, $request)) {
            return $this->redirect($url->generateCanonical($property), 301);
        } elseif ($url->isRefererFromCurrentHost($request)) {
            $show_back_button = true;
        }


        if($request->isMethod('POST'))
        {
            $this->addFlash('info','Mensage Ha sido envia correctamente');

            $this->saveData($request->request->all());
        }

        return $this->render('property/show.html.twig',
            [
                'site' => $this->site($request),
                'property' => $property,
                'properties' => $repository->findSimilarProperties($property),
                'number_of_photos' => \count($property->getPhotos()),
                'show_back_button' => $show_back_button ?? false,
            ]
        );
    }

    function saveData($data){

        $contact = new Contact();

        $contact->setEmail($data['email']);
        $contact->setFullname($data['name']);
        $contact->setLegal(true);
        $contact->setTelefon($data['phone']);
        $contact->setContent($data['url'] . "\r" .  $data['message'] );
        $em =$this->doctrine->getManager();
        $em->persist($contact);
        $em->flush();


    }
}
