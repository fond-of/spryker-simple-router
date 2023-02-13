<?php

namespace FondOfSpryker\Shared\SimpleRouter;

interface SimpleRouterConstants
{
    /**
     * Specification:
     * - An array of excluded route prefixes
     * - Example: `['/payone' => ['GET'], '/feed' => ['GET'], '/_profiler' => ['GET'], '/form' => ['GET']]`
     *
     * @api
     *
     * @var string
     */
    public const YVES_EXCLUDED_ROUTE_PREFIXES = 'SIMPLE_ROUTER:YVES_EXCLUDED_ROUTE_PREFIXES';

    /**
     * @var string
     */
    public const YVES_BLACKLISTED_URLS = 'SIMPLE_ROUTER:YVES_BLACKLISTED_URLS';

    /**
     * @var string
     */
    public const BLACKLISTED_LOCALE_PREFIXES = 'SIMPLE_ROUTER:YVES_BLACKLISTED_ROUTE_LOCALE_PREFIXES';

    /**
     * @var string
     */
    public const SHOULD_REDIRECT_CRAWLER = 'SIMPLE_ROUTER:SHOULD_REDIRECT_CRAWLER';

    /**
     * @var string
     */
    public const REDIRECT_TYPE = 'externalRedirect';

    /**
     * @var string
     */
    public const INTERNAL_REDIRECT_TYPE = 'internalRedirect';

    /**
     * @var string
     */
    public const RESOURCE_NOT_FOUND_TYPE = 'resourceNotFound';

    /**
     * @var string
     */
    public const URL_LANG_PATTERN = '{lang-prefix}';
}
