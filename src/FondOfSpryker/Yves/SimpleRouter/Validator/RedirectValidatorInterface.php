<?php

namespace FondOfSpryker\Yves\SimpleRouter\Validator;


/**
 * Class RedirectValidator
 * @package FondOfSpryker\Yves\SimpleRouter\Validator
 *
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterConfig getConfig()
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 */
interface RedirectValidatorInterface
{
    /**
     * @return bool
     */
    public function isLanguageRedirectAllowed(): bool;

    /**
     * @param  string  $pathInfo
     * @param  string  $method
     *
     * @return bool
     */
    public function isLanguageValidationRedirectAllowed(string $pathInfo, string $method): bool;

    /**
     * @return bool
     */
    public function isRemoveTrailingSlashRedirectAllowed(): bool;

    /**
     * @param  string  $pathInfo
     * @param  string  $method
     *
     * @return bool
     */
    public function isExcludedRedirectAllowed(string $pathInfo, string $method): bool;

    /**
     * @return bool
     */
    public function redirectCrawler(): bool;
}
