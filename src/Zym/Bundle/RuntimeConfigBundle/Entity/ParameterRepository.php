<?php
namespace Zym\Bundle\RuntimeConfigBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OpenSky\Bundle\RuntimeConfigBundle\Model\ParameterProviderInterface;

class ParameterRepository extends AbstractEntityRepository 
                          implements ParameterProviderInterface
{
    /**
     * Find parameters
     *
     * @param array $critera
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findParameters(array $criteria = null, $page = 1, $limit = 10, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('p');
        $this->setQueryOptions($qb, $criteria, $orderBy);
    
        $query     = $qb->getQuery();
        $paginator = $this->getPaginator();
    
        return $paginator->paginate($query, $page, $limit);
    }
    
    /**
     * @see OpenSky\Bundle\RuntimeConfigBundle\Model\ParameterProviderInterface::getParametersAsKeyValueHash()
     */
    public function getParametersAsKeyValueHash()
    {
        $results = $this->createQueryBuilder('p')
            ->select('p.name', 'p.value')
            ->getQuery()
            ->getResult();
    
        $parameters = array();
    
        foreach ($results as $result) {
            $parameters[$result['name']] = $result['value'];
        }
    
        return $parameters;
    }
}