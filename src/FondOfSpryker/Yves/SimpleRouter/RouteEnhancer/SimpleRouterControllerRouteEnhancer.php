<?php

namespace FondOfSpryker\Yves\SimpleRouter\RouteEnhancer;

use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\ResourceCreatorPluginInterface;
use FondOfSpryker\Yves\SimpleRouter\Exception\DefaultResourceCreatorNotSetException;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;
use Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface;
use Symfony\Component\HttpFoundation\Request;

class SimpleRouterControllerRouteEnhancer implements RouteEnhancerInterface
{
    /**
     * @var \FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\ResourceCreatorPluginInterface[]
     */
    protected $resourceCreatorPlugins;

    /**
     * @param \FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\ResourceCreatorPluginInterface[] $resourceCreatorPlugins
     */
    public function __construct(array $resourceCreatorPlugins)
    {
        $this->resourceCreatorPlugins = $resourceCreatorPlugins;
    }

    /**
     * @param array $defaults
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function enhance(array $defaults, Request $request): array
    {
        foreach ($this->resourceCreatorPlugins as $resourceCreator) {
            if ($resourceCreator->isDefault() === false && $defaults['type'] === $resourceCreator->getType()) {
                $resourceCreator->modifyRequest($request);

                return $this->createResource($resourceCreator, $defaults);
            }
        }

        return $this->createResource($this->getDefaultResourceCreator(), $defaults);
    }

    /**
     * @param \FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\ResourceCreatorPluginInterface $resourceCreator
     * @param array $data
     *
     * @return array
     */
    protected function createResource(ResourceCreatorPluginInterface $resourceCreator, array $data)
    {
        $bundleControllerAction = new BundleControllerAction(
            $resourceCreator->getModuleName(),
            $resourceCreator->getControllerName(),
            $resourceCreator->getActionName()
        );
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->resolve($bundleControllerAction);
        $actionName = $resourceCreator->getActionName();
        if (strrpos($actionName, 'Action') === false) {
            $actionName .= 'Action';
        }

        $resourceCreatorResult['meta'] = $data;
        $resourceCreatorResult['_controller'] = [$controller, $actionName];
        $resourceCreatorResult['_route'] = $routeResolver->resolve();

        return $resourceCreatorResult;
    }

    /**
     * @throws \FondOfSpryker\Yves\SimpleRouter\Exception\DefaultResourceCreatorNotSetException
     *
     * @return \FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\ResourceCreatorPluginInterface
     */
    protected function getDefaultResourceCreator(): ResourceCreatorPluginInterface
    {
        foreach ($this->resourceCreatorPlugins as $resourceCreatorPlugin) {
            if ($resourceCreatorPlugin->isDefault()) {
                return $resourceCreatorPlugin;
            }
        }

        throw new DefaultResourceCreatorNotSetException('Please set "isDefault = true" for one of the registered ResourceCreators in RouterDependencyProvider');
    }
}
