<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RequestMatcher;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class CrawlerRequestMatcherPlugin
 *
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 */
class ValidateLocalePrefixRequestMatcherPlugin extends AbstractPlugin implements RequestMatcherPluginInterface
{
    /**
     * @var string
     */
    protected const USER_DEFAULT_LOCALE_PREFIX = 'USER_DEFAULT_LOCALE_PREFIX';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        $pathInfo = $request->getPathInfo();
        if ($this->hasValidLocalePrefix($pathInfo) === false) {
            return $this->redirectWithLocale($request, $pathInfo);
        }

        return [];
    }

    /**
     * @param string $pathInfo
     *
     * @return bool
     */
    protected function hasValidLocalePrefix(string $pathInfo): bool
    {
        $explodePath = explode('/', trim($pathInfo, '/'));

        // @phpstan-ignore-next-line
        if (count($explodePath) === 0) {
            return false;
        }

        $isLocaleAvailable = $this->isLocaleAvailableInCurrentStore($explodePath[0]);

        if ($isLocaleAvailable) {
            try {
                $this->setUserDefaultLocalePrefix($explodePath[0]);
            } catch (Throwable $t) {
                // catch session exceptions
            }
        }

        return $isLocaleAvailable;
    }

    /**
     * @param string $locale
     *
     * @return void
     */
    protected function setUserDefaultLocalePrefix(string $locale): void
    {
        $this->getFactory()->getSessionClient()->set(static::USER_DEFAULT_LOCALE_PREFIX, $locale);
    }

    /**
     * @param string $locale
     *
     * @return bool
     */
    protected function isLocaleAvailableInCurrentStore(string $locale): bool
    {
        return array_key_exists($locale, $this->getFactory()->getStoreClient()->getCurrentStore()->getAvailableLocaleIsoCodes());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $additionalPath
     *
     * @return array<string>
     */
    protected function redirectWithLocale(Request $request, string $additionalPath = ''): array
    {
        $defaultLocale = $this->getDefaultStoreRouteLocalePrefix();
        $uri = $request->getSchemeAndHttpHost() . '/' . $this->getUriLocale($defaultLocale);
        $uri = $this->appendQueryStringToUri($uri . $additionalPath, $request);

        return $this->createRedirect($uri);
    }

    /**
     * @param string $defaultLocale
     *
     * @return string
     */
    protected function getUriLocale(string $defaultLocale = 'en'): string
    {
        $browserLocale = $this->getUserDefaultLocalePrefix() ?? $this->detectBrowserLocale();

        if ($this->isLocaleAvailableInCurrentStore($browserLocale)) {
            return $browserLocale;
        }

        return $defaultLocale;
    }

    /**
     * @return string|null
     */
    protected function detectBrowserLocale(): ?string
    {
        return $this->getFactory()->createBrowserDetectorLanguage()->getLanguage();
    }

    /**
     * @param string $fallbackRoutePrefixLocale
     *
     * @return string
     */
    protected function getDefaultStoreRouteLocalePrefix(string $fallbackRoutePrefixLocale = 'en'): string
    {
        $storeLocales = $this->getFactory()->getStoreInstance()->getLocales();
        if (!is_array($storeLocales)) {
            return $fallbackRoutePrefixLocale;
        }

        $storeLocaleRoutePrefixes = array_keys($storeLocales);

        if (!$storeLocaleRoutePrefixes) {
            return $fallbackRoutePrefixLocale;
        }

        return array_shift($storeLocaleRoutePrefixes);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(): Request
    {
        return $this->getApplication()['request'];
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
     * @return string|null
     */
    protected function getUserDefaultLocalePrefix(): ?string
    {
        return $this->getFactory()->getSessionClient()->get(static::USER_DEFAULT_LOCALE_PREFIX);
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
