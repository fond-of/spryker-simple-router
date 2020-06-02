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
        if ($this->hasPrefixAndUsesMethod($request->getPathInfo(), $request->getMethod())) {
            throw new ResourceNotFoundException();
        }

        return [];
    }

    /**
     * @param string $pathInfo
     * @param string $method
     *
     * @return bool
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
     * @param string $pathInfo
     * @param string $startingPart
     *
     * @return bool
     */
    protected function pathStartsWith(string $pathInfo, string $startingPart): bool
    {
        return strpos($pathInfo, $startingPart) === 0;
    }

    /**
     * @param string $pathInfo
     *
     * @return string
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
     * @param string $method
     * @param $methods
     *
     * @return bool
     */
    protected function useMethod(string $method, $methods): bool
    {
        return in_array($method, $methods, true);
    }
}
