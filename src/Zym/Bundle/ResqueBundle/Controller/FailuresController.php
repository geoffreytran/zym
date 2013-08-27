<?php

namespace Zym\Bundle\ResqueBundle\Controller;

use Zym\Bundle\ResqueBundle\Failure\Redis as FailedJobs;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FailuresController extends Controller
{
    /**
     * @Route(
     *     ".{_format}", 
     *     name="zym_resque_failures",
     *     defaults={
     *         "_format" = "html"
     *     }
     * )
     * @Template()
     */
    public function indexAction()
    {
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');
        
        $resque = $this->get('zym_resque.resque');
        //$resque->enqueue(new \Zym\Bundle\ResqueBundle\Tests\ExceptionJob());
        
        $start    = FailedJobs::count() - ($page * $limit);
        $failures = array_reverse(FailedJobs::all($start < 0 ? 0 : $start, $start < 0 ? $limit - abs($start) : $limit));

        return array(
            'resque'   => $resque,
            'failures' => $failures,
            'page'     => (int)$page,
            'limit'    => (int)$limit,
            'count'    => FailedJobs::count()
        );
    }
}
