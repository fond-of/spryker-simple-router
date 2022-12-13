<?php

namespace FondOfSpryker\Yves\SimpleRouter\Router;

use Symfony\Cmf\Component\Routing\DynamicRouter as SymfonyDynamicRouter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 */
class SimpleRouter extends SymfonyDynamicRouter
{
    /**
     * @param \Symfony\Component\Routing\Matcher\RequestMatcherInterface $requestMatcher
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param array<\Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface> $routeEnhancers
     */
    public function __construct(RequestMatcherInterface $requestMatcher, UrlGeneratorInterface $urlGenerator, array $routeEnhancers = [])
    {
        parent::__construct(new RequestContext(), $requestMatcher, $urlGenerator);

        $this->addRouteEnhancers($routeEnhancers);
    }

    /**
     * @param array $routeEnhancers
     *
     * @return void
     */
    protected function addRouteEnhancers(array $routeEnhancers)
    {
        foreach ($routeEnhancers as $routeEnhancer) {
            $this->addRouteEnhancer($routeEnhancer);
        }
    }
}
