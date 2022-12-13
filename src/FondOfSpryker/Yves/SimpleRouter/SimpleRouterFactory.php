<?php

namespace FondOfSpryker\Yves\SimpleRouter;

use FondOfSpryker\Yves\SimpleRouter\Dependency\Client\SimpleRouterToSessionClientInterface;
use FondOfSpryker\Yves\SimpleRouter\Dependency\Client\SimpleRouterToStoreClientInterface;
use FondOfSpryker\Yves\SimpleRouter\RequestMatcher\SimpleRouterRequestMatcher;
use FondOfSpryker\Yves\SimpleRouter\RouteEnhancer\SimpleRouterControllerRouteEnhancer;
use FondOfSpryker\Yves\SimpleRouter\Router\SimpleRouter;
use FondOfSpryker\Yves\SimpleRouter\UrlGenerator\SimpleRouterUrlGenerator;
use FondOfSpryker\Yves\SimpleRouter\Validator\RedirectValidator;
use FondOfSpryker\Yves\SimpleRouter\Validator\RedirectValidatorInterface;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Sinergi\BrowserDetector\Language;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 */
class SimpleRouterFactory extends AbstractFactory
{
    /**
     * @return \Sinergi\BrowserDetector\Language
     */
    public function createBrowserDetectorLanguage(): Language
    {
        return new Language();
    }

    /**
     * @return \Jaybizzle\CrawlerDetect\CrawlerDetect
     */
    public function createCrawlerDetect(): CrawlerDetect
    {
        return new CrawlerDetect();
    }

    /**
     * @return array<\Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface>
     */
    public function createRouteEnhancer(): array
    {
        return [
            new SimpleRouterControllerRouteEnhancer($this->getSimpleRouterResourceCreatorPlugins()),
        ];
    }

    /**
     * @return \FondOfSpryker\Yves\SimpleRouter\Dependency\Client\SimpleRouterToSessionClientInterface
     */
    public function getSessionClient(): SimpleRouterToSessionClientInterface
    {
        return $this->getProvidedDependency(SimpleRouterDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \FondOfSpryker\Yves\SimpleRouter\Dependency\Client\SimpleRouterToStoreClientInterface
     */
    public function getStoreClient(): SimpleRouterToStoreClientInterface
    {
        return $this->getProvidedDependency(SimpleRouterDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStoreInstance(): Store
    {
        return $this->getProvidedDependency(SimpleRouterDependencyProvider::DIRTY_STORE_INSTANCE);
    }

    /**
     * @return array<\FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface>
     */
    public function getSimpleRouterRequestMatcherPlugins(): array
    {
        return $this->getProvidedDependency(SimpleRouterDependencyProvider::PLUGIN_REQUEST_MATCHER_SIMPLE_ROUTER);
    }

    /**
     * @return array<\FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\ResourceCreatorPluginInterface>
     */
    public function getSimpleRouterResourceCreatorPlugins(): array
    {
        return $this->getProvidedDependency(SimpleRouterDependencyProvider::PLUGIN_RESOURCE_CREATORS_SIMPLE_ROUTER);
    }

    /**
     * @return \FondOfSpryker\Yves\SimpleRouter\Router\SimpleRouter
     */
    public function createSimpleRouter(): SimpleRouter
    {
        return new SimpleRouter(
            $this->createSimpleRouterRequestMatcher(),
            $this->createSimpleRouterUrlGenerator(),
            $this->createRouteEnhancer(),
        );
    }

    /**
     * @return \FondOfSpryker\Yves\SimpleRouter\Validator\RedirectValidatorInterface
     */
    public function createRedirectValidator(): RedirectValidatorInterface
    {
        return new RedirectValidator($this->getStoreInstance());
    }

    /**
     * @return \FondOfSpryker\Yves\SimpleRouter\RequestMatcher\SimpleRouterRequestMatcher
     */
    protected function createSimpleRouterRequestMatcher(): SimpleRouterRequestMatcher
    {
        return new SimpleRouterRequestMatcher($this->getSimpleRouterRequestMatcherPlugins());
    }

    /**
     * @return \FondOfSpryker\Yves\SimpleRouter\UrlGenerator\SimpleRouterUrlGenerator
     */
    protected function createSimpleRouterUrlGenerator(): SimpleRouterUrlGenerator
    {
        return new SimpleRouterUrlGenerator();
    }
}
