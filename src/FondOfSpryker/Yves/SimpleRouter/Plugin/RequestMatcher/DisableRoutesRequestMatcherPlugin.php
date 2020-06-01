<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RequestMatcher;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CrawlerRequestMatcherPlugin
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 */
class DisableRoutesRequestMatcherPlugin extends AbstractPlugin implements RequestMatcherPluginInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return array
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function handle(Request $request): array
    {
        if ($this->hasPrefixAndUsesMethod($request->getPathInfo(), $request->getMethod())) {
            return ['type' => SimpleRouterConstants::RESOURCE_NOT_FOUND_TYPE];
        }

        return [];
    }

    /**
     * @param  string  $pathInfo
     * @param  string  $method
     *
     * @return bool
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function hasPrefixAndUsesMethod(string $pathInfo, string $method): bool
    {
        $pathInfo = $this->removeLanguageStuffFronmPathInfo($pathInfo);
        foreach ($this->getConfig()->getExcludedRoutePrefixes() as $path => $methods) {
            if ($this->pathStartsWith($pathInfo, $path) && $this->useMethod($method, $methods)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string  $pathInfo
     * @param  string  $startingPart
     *
     * @return bool
     */
    protected function pathStartsWith(string $pathInfo, string $startingPart): bool
    {
        return strpos($pathInfo, $startingPart) === 0;
    }

    /**
     * @param  string  $pathInfo
     *
     * @return string
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function removeLanguageStuffFronmPathInfo(string $pathInfo): string
    {
        $locales = array_keys($this->getFactory()->getStoreInstance()->getLocales());
        foreach ($locales as $locale) {
            $pathInfo = str_replace(sprintf('/%s', $locale), '', $pathInfo);
        }
        return $pathInfo;
    }

    /**
     * @param  string  $method
     * @param $methods
     *
     * @return bool
     */
    protected function useMethod(string $method, $methods): bool
    {
        return in_array($method, $methods, true);
    }
}
