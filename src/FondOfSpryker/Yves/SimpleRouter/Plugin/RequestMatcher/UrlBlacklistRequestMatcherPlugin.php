<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RequestMatcher;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CrawlerRequestMatcherPlugin
 *
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 */
class UrlBlacklistRequestMatcherPlugin extends AlwaysRedirectFromBlacklistedLocaleRequestMatcherPlugin
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $storeInstance;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        $redirect = $this->isUrlBlacklisted($request->getPathInfo());

        if ($redirect !== []) {
            return $this->redirect($request, $redirect[static::TO]);
        }

        return [];
    }

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    protected function isUrlBlacklisted(string $pathInfo): array
    {
        $blacklist = $this->cleanBlacklist($this->getConfig()->getBlacklistedUrls());
        $searchString = $this->prepareSearchString($pathInfo);

        $redirect = [];
        if (array_key_exists($searchString, $blacklist)) {
            $redirect = [
                static::FROM => $pathInfo,
                static::TO => $this->prepareRedirectUrl($blacklist[$searchString]),
            ];
        }

        return $redirect;
    }

    /**
     * @param array $blacklist
     *
     * @return array
     */
    protected function cleanBlacklist(array $blacklist): array
    {
        $cleanedBlacklist = [];
        foreach ($blacklist as $key => $value) {
            $cleanedBlacklist[$this->cleanString($key)] = $this->cleanString($value);
        }

        return $cleanedBlacklist;
    }

    /**
     * @param string $pathInfo
     *
     * @return string
     */
    protected function prepareSearchString(string $pathInfo): string
    {
        $langPrefix = $this->getCurrentLocale();

        return $this->cleanString(str_replace($langPrefix, SimpleRouterConstants::URL_LANG_PATTERN, $pathInfo));
    }

    /**
     * @param string $redirectPattern
     *
     * @return string
     */
    protected function prepareRedirectUrl(string $redirectPattern): string
    {
        $langPrefix = $this->getCurrentLocale();

        return sprintf(
            '/%s',
            $this->cleanString(str_replace(SimpleRouterConstants::URL_LANG_PATTERN, $langPrefix, $redirectPattern)),
        );
    }

    /**
     * @return string
     */
    protected function getCurrentLocale(): string
    {
        if ($this->storeInstance === null) {
            $this->storeInstance = Store::getInstance();
        }

        $language = array_search($this->storeInstance->getCurrentLocale(), $this->storeInstance->getLocales());
        if ($language !== false) {
            return $language;
        }

        return $this->storeInstance->getCurrentLanguage();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $redirectToUrl
     *
     * @return array
     */
    protected function redirect(Request $request, string $redirectToUrl): array
    {
        $uri = sprintf(
            '%s/%s',
            $request->getSchemeAndHttpHost(),
            $this->cleanString($redirectToUrl),
        );

        $uri = $this->appendQueryStringToUri($uri, $request);

        return $this->createRedirect($uri);
    }
}
