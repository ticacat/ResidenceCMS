<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;

final class FilterRepository extends PropertyRepository
{

    public function findByFilterQuery(array $params)
    {
        $qb = $this->createQueryBuilder('p');


        if ($this->security->isGranted('ROLE_ADMIN')) {
            if (\in_array($params['state'], ['published', 'private', 'pending', 'rejected'], true)) {
                $qb->where("p.state = '".$params['state']."'");
            }
        } else {
            $qb->where("p.state = 'published'");
        }




        // show_slider_homepage
        if (isset($params['show_slider_homepage'])) {
            $qb->andWhere(' p.show_slider_homepage = 1 ');
        }


        if (isset($params['range_price'])) {
            list($min,$max) = explode('-',$params['range_price']);
            $qb->andWhere(' p.price BETWEEN :minvalue AND :maxvalue ')
                ->setParameter('minvalue', $min, ParameterType::INTEGER)
                ->setParameter('maxvalue', $max,ParameterType::INTEGER)
            ;
        }

        // Number of bedrooms
        if ($params['bedrooms'] > 3) {
            $qb->andWhere('p.bedrooms_number > 3');
        } elseif ($params['bedrooms'] > 0) {
            $qb->andWhere('p.bedrooms_number = '.(int) $params['bedrooms']);
        }



        // Additional params
        foreach (['city', 'deal_type', 'category'] as $parameter) {
            if ($params[$parameter] > 0) {
                $qb->andWhere('p.'.$parameter.' = '.(int) $params[$parameter]);
            }
        }

        if ($params['feature'] > 0) {
            $qb->innerJoin('p.features', 'pf');
            $qb->andWhere(':feature MEMBER OF p.features')
                ->setParameter(':feature', (int) $params['feature']);
        }





        // Sort by
        ('id' === $params['sort_by'])
            ? $qb->orderBy('p.id', 'DESC')
            : $qb->orderBy('p.priority_number', 'DESC');


        return $qb;
    }

    public function findByFilter(array $params,$onlyPremium =false): PaginationInterface
    {
        $qb = $this->findByFilterQuery($params);

        return $this->createPaginator($qb->getQuery(), $params['page']);
    }


    public function findByFilterWithPremium(array $params,$onlyPremium =false): PaginationInterface
    {
        $qb = $this->findByFilterQuery($params);

        if($onlyPremium)
        {
            $qb->andWhere('(p.level <= :level AND p.level > 0)')
                ->setParameter(':level', (int) $params['level']);
        }else{

            $qb->andWhere('(p.level is null or p.level = 0) ');
        }






        return $this->createPaginator($qb->getQuery(), $params['page']);
    }


}
