<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RequestMatcher;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CrawlerRequestMatcherPlugin
 *
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 */
class AddTrailingSlashForHomeAndRedirectRequestMatcherPlugin extends AbstractPlugin implements RequestMatcherPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        if ($this->hasTrailingSlash($request->getPathInfo()) === false && $this->getFactory()->createRedirectValidator()->isHome($request)) {
            return $this->redirectWithTrailingSlash($request);
        }

        return [];
    }

    /**
     * @param string $pathinfo
     *
     * @return bool
     */
    protected function hasTrailingSlash(string $pathinfo): bool
    {
        return substr($pathinfo, -1) == '/';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function redirectWithTrailingSlash(Request $request): array
    {
        $uri = sprintf('%s/', $request->getPathInfo());
        $uri = $this->appendQueryStringToUri($uri, $request);

        return $this->createRedirect($uri);
    }

    /**
     * @param string $uri
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function appendQueryStringToUri(string $uri, Request $request): string
    {
        $queryString = $request->getQueryString();
        if (is_string($queryString) && strlen($queryString) > 0) {
            return $uri . '?' . $queryString;
        }

        return $uri;
    }

    /**
     * @param string $toUri
     * @param int $statusCode
     *
     * @return array<string>
     */
    protected function createRedirect(string $toUri, int $statusCode = 301): array
    {
        return ['to_url' => $toUri, 'status' => $statusCode, 'type' => SimpleRouterConstants::REDIRECT_TYPE];
    }
}
