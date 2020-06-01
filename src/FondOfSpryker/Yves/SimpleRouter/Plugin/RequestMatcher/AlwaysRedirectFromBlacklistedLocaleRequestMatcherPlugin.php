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
class AlwaysRedirectFromBlacklistedLocaleRequestMatcherPlugin extends AbstractPlugin implements RequestMatcherPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        $pathInfo = $request->getPathInfo();

        if ($this->isLocaleBlacklisted($pathInfo)) {
            return $this->redirectToLocaleDe($pathInfo, $request);
        }

        return [];
    }

    /**
     * @param string $pathInfo
     *
     * @return bool
     */
    protected function isLocaleBlacklisted(string $pathInfo): bool
    {
        foreach ($this->getConfig()->getBlacklistedLocale() as $blacklistedLocale) {
            if ($this->pathStartsWith($pathInfo, sprintf('/%s', ltrim(rtrim($blacklistedLocale, '/'), '/'))) === true) {
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
        $check = strpos($pathInfo, $startingPart) === 0;
        if ($check === false) {
            return false;
        }
        $cleanedPath = preg_replace('/' . preg_quote($startingPart, '/') . '/', '', $pathInfo, 1);

        if ($cleanedPath === '' || strpos($cleanedPath, '/') === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param string $pathInfo
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string[]
     */
    protected function redirectToLocaleDe(string $pathInfo, Request $request): array
    {
        $pathInfoWithoutLocale = substr($pathInfo, 3);

        $uri = sprintf(
            '%s/%s%s',
            $request->getSchemeAndHttpHost(),
            'de',
            $pathInfoWithoutLocale
        );

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
     * @return string[]
     */
    protected function createRedirect(string $toUri, int $statusCode = 301): array
    {
        return ['to_url' => $toUri, 'status' => $statusCode, 'type' => SimpleRouterConstants::REDIRECT_TYPE];
    }
}
