<?php

namespace FondOfSpryker\Yves\SimpleRouter\Validator;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * Class RedirectValidator
 * @package FondOfSpryker\Yves\SimpleRouter\Validator
 *
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 */
class RedirectValidator extends AbstractPlugin implements RedirectValidatorInterface
{
    //ToDo: after Upgrade create real validator

    /**
     * @return bool
     */
    public function isLanguageRedirectAllowed(): bool
    {
        return $this->redirectCrawler();
    }

    /**
     * @param  string  $pathInfo
     * @param  string  $method
     *
     * @return bool
     */
    public function isLanguageValidationRedirectAllowed(string $pathInfo, string $method): bool
    {
        return $this->isExcludedRedirectAllowed($pathInfo, $method);
    }

    /**
     * @return bool
     */
    public function isRemoveTrailingSlashRedirectAllowed(): bool
    {
        return $this->redirectCrawler();
    }

    /**
     * @param  string  $pathInfo
     * @param  string  $method
     *
     * @return bool
     */
    public function isExcludedRedirectAllowed(string $pathInfo, string $method): bool
    {
        return $this->redirectCrawler() === true && $this->hasPrefixAndUsesMethod($pathInfo, $method) === true;
    }

    /**
     * @return bool
     */
    public function redirectCrawler(): bool
    {
        if ($this->getFactory()->createCrawlerDetect()->isCrawler() === false) {
            return true;
        }

        return $this->getConfig()->redirectCrawler();
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
