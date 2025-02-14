<?php

declare(strict_types=1);

namespace App\Transformer;

use Symfony\Component\HttpFoundation\Request;

final class RequestToArrayTransformer
{
    public function transform(Request $request): array
    {
        $params = [];
        $params['city'] = $request->query->getInt('city', 0);
        $params['deal_type'] = $request->query->getInt('deal_type', 0);
        $params['category'] = $request->query->getInt('category', 0);
        $params['bedrooms'] = $request->query->getInt('bedrooms', 0);
        $params['guests'] = $request->query->getInt('guests', 0);
        $params['feature'] = $request->query->getInt('feature', 0);
        $params['sort_by'] = $request->query->get('sort_by', 'priority_number');
        $params['state'] = $request->query->get('state', 'published');
        $params['page'] = $request->query->getInt('page', 1);


        $show_slider_homepage = $request->query->get('show_slider_homepage', false);
        if(!in_array($show_slider_homepage,[0,false,'false','0','']))
        {
            $params['show_slider_homepage'] = 1;
        }

        $params['minprice']  = $request->query->get('minprice','0');
        $params['maxprice']  = $request->query->get('maxprice','10000000');



        return $params;
    }
}
