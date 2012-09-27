<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 */

namespace Zym\Bundle\FieldBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Field Type Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 */
class FieldTypeRepository extends AbstractEntityRepository
{
    /**
     * Find Fields
     *
     * @param array $criteria
     * @param array $orderBy
     * @return Paginator
     */
    public function findFieldTypes(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('ft');
        $this->setQueryBuilderOptions($qb, $criteria, $orderBy);

        $query     = $qb->getQuery();
        $paginator = $this->getPaginator();

        return $paginator->paginate($query, $page, $limit);
    }
}