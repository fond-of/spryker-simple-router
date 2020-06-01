<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\Router;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\RouterExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 */
class SimpleRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getFactory()->createSimpleRouter();
    }
}
