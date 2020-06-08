<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RequestMatcher;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface;
use FondOfSpryker\Yves\SimpleRouter\Exception\WrongConfigurationException;
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
    public const DEFAULT_REDIRECT_LOCALE = '/en';

    protected const FROM = 'from';
    protected const TO = 'to';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        $pathInfo = $request->getPathInfo();
        $defaultLocale = $this->isLocaleBlacklisted($pathInfo);

        if ($defaultLocale !== []) {
            return $this->redirectToLocale($pathInfo, $request, $defaultLocale);
        }

        return [];
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    protected function isLocaleBlacklisted(string $pathInfo): array
    {
        foreach ($this->getConfig()->getBlacklistedLocale() as $blacklistedLocale => $redirectTo) {
            if ($this->pathStartsWith($pathInfo, sprintf('/%s', $this->cleanString($blacklistedLocale))) === true) {
                return [
                    static::FROM => $blacklistedLocale,
                    static::TO => $redirectTo,
                ];
            }
        }

        return [];
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
     * @param array $redirectLocale
     *
     * @return string[]
     */
    protected function redirectToLocale(string $pathInfo, Request $request, array $redirect): array
    {
        $redirectLocale = $this->prepareRedirectLocale($redirect);
//        $pathInfoWithoutLocale = $this->cleanString(substr($pathInfo, strlen($redirect[static::FROM])));

        $uri = sprintf(
            '%s/%s',
            $request->getSchemeAndHttpHost(),
            $redirectLocale
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

    /**
     * @param string|array $redirectLocale
     *
     * @throws \FondOfSpryker\Yves\SimpleRouter\Exception\WrongConfigurationException
     *
     * @return string
     */
    protected function prepareRedirectLocale(array $redirectLocale): string
    {
        if ($redirectLocale === [] || is_numeric($redirectLocale[static::FROM])) {
            throw new WrongConfigurationException('Please configure mapping like [from => to] eg. $config[SimpleRouterConstants::BLACKLISTED_LOCALE_PREFIXES] = [\'/ch\' => \'/de\'];');
        }

        if (empty($redirectLocale[static::TO])) {
            $redirectLocale[static::TO] = static::DEFAULT_REDIRECT_LOCALE;
        }

        return $this->cleanString($redirectLocale[static::TO]);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function cleanString(string $string): string
    {
        $string = ltrim($string, '/');
        $string = rtrim($string, '/');

        return $string;
    }
}
