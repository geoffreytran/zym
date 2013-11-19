<?php
namespace Zym\Bundle\FrameworkBundle\Form\Extension\Csrf\Type;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\Form\Extension\Csrf\EventListener\CsrfValidationListener;
use Symfony\Component\Form\Extension\Csrf\Type\FormTypeCsrfExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class RestFormTypeCsrfExtension extends FormTypeCsrfExtension
{
    /**
     * @var Request
     */
    private $request;

    /**
     * Construct
     *
     * @param CsrfProviderInterface $defaultCsrfProvider
     * @param bool                  $defaultEnabled
     * @param string                $defaultFieldName
     * @param TranslatorInterface   $translator
     * @param null                  $translationDomain
     * @param Request               $request
     */
    public function __construct(CsrfProviderInterface $defaultCsrfProvider, $defaultEnabled = true, $defaultFieldName = '_token', TranslatorInterface $translator = null, $translationDomain = null, Request $request = null)
    {
        if ($request->isXmlHttpRequest() || $request->getRequestFormat() !== 'html') {
            $defaultEnabled = false;
        }

        parent::__construct($defaultCsrfProvider, $defaultEnabled, $defaultFieldName, $translator, $translationDomain);
    }
}