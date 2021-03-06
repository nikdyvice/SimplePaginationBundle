<?php

namespace AshleyDawson\SimplePaginationBundle\Twig;

use AshleyDawson\SimplePagination\Pagination;

/**
 * Class SimplePaginationExtension
 *
 * @package AshleyDawson\SimplePaginationBundle\Twig
 * @author Ashley Dawson <ashley@ashleydawson.co.uk>
 */
class SimplePaginationExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    /**
     * @var string
     */
    private $defaultTemplate;

    /**
     * Constructor
     *
     * @param string $defaultTemplate
     */
    public function __construct($defaultTemplate)
    {
        $this->defaultTemplate = $defaultTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('simple_pagination_render', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twigEnvironment = $environment;
    }

    /**
     * Render the pagination
     *
     * @param Pagination $pagination
     * @param string $routeName
     * @param string $pageParameterName
     * @param array $queryParameters
     * @param string|null $template If null is passed then the default template is used
     * @return string
     */
    public function render(
        Pagination $pagination, $routeName, $pageParameterName = 'page', array $queryParameters = array(), $template = null)
    {
        if (null === $template) {
            $template = $this->defaultTemplate;
        }

        return $this->twigEnvironment->render($template, array(
            'pagination' => $pagination,
            'routeName' => $routeName,
            'pageParameterName' => $pageParameterName,
            'queryParameters' => $queryParameters,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ashley_dawson_simple_pagination_extension';
    }
}