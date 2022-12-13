<?php

namespace FondOfSpryker\Yves\SimpleRouter\RequestMatcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

class SimpleRouterRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var array<\FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface>
     */
    protected $requestMatcherPlugins;

    /**
     * @param array<\FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface> $requestMatcherPlugins
     */
    public function __construct(array $requestMatcherPlugins)
    {
        $this->requestMatcherPlugins = $requestMatcherPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     *
     * @return array
     */
    public function matchRequest(Request $request): array
    {
        foreach ($this->requestMatcherPlugins as $requestMatcherPlugin) {
            $data = $requestMatcherPlugin->handle($request);
            if ($data !== []) {
                return $data;
            }
        }

        $info = "this request\n$request";

        throw new ResourceNotFoundException("None of the routers in the chain matched $info");
    }
}
