<?php
/**
 * Created by PhpStorm.
 * User: nevada
 * Date: 24.09.14
 * Time: 10:53
 */

namespace Ant\MediaBundle\Entity;
use Doctrine\ORM\EntityRepository;


class GalleryRepository extends EntityRepository {

    public function findAll(){
        return $this->findBy(array(), array('createdAt'=>'DESC'));

    }

    public function findOther($id, $max){

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('g')
            ->from('AntMediaBundle:Gallery', 'g')
            ->setParameter(1, $id)
            ->where('g.id != ?1', 'g.enabled = true')
            ->setMaxResults($max)
            ->orderBy('g.id', 'DESC')
        ;

        $query = $qb->getQuery();

        $result = $query->getResult();
        return $result;

    }

} 