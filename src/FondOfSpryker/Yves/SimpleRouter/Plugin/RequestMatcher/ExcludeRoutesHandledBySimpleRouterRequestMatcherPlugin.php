<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RequestMatcher;

use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class CrawlerRequestMatcherPlugin
 *
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 */
class ExcludeRoutesHandledBySimpleRouterRequestMatcherPlugin extends AbstractPlugin implements RequestMatcherPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        if ($this->getFactory()->createRedirectValidator()->isExcludedRedirectAllowed($request->getPathInfo(), $request->getMethod())) {
            throw new ResourceNotFoundException();
        }

        return [];
    }
}
