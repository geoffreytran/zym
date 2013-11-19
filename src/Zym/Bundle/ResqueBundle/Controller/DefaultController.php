<?php

namespace Zym\Bundle\ResqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Zym\Bundle\ResqueBundle\Tests\ExceptionJob;

class DefaultController extends Controller
{
    /**
     * @Route("", name="zym_resque")
     * @Template()
     */
    public function indexAction()
    {
        $a = new ExceptionJob();
        $this->get('zym_resque.resque')->enqueueOnce($a);

        return array(
            'resque'  => $this->get('zym_resque.resque'),
        );
    }

    /**
     * @Route("queues", name="zym_resque_list_queues")
     * @Template()
     */
    public function listQueuesAction()
    {
        $resque = $this->get('zym_resque.resque');

        return array(
            'queues'  => $resque->getQueues(),
        );
    }
}
