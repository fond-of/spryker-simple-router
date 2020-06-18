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
     */
    public const YVES_EXCLUDED_ROUTE_PREFIXES = 'SIMPLE_ROUTER:YVES_EXCLUDED_ROUTE_PREFIXES';

    public const YVES_BLACKLISTED_URLS = 'SIMPLE_ROUTER:YVES_BLACKLISTED_URLS';

    public const BLACKLISTED_LOCALE_PREFIXES = 'SIMPLE_ROUTER:YVES_BLACKLISTED_ROUTE_LOCALE_PREFIXES';

    public const SHOULD_REDIRECT_CRAWLER = 'SIMPLE_ROUTER:SHOULD_REDIRECT_CRAWLER';

    public const REDIRECT_TYPE = 'externalRedirect';

    public const INTERNAL_REDIRECT_TYPE = 'internalRedirect';

    public const RESOURCE_NOT_FOUND_TYPE = 'resourceNotFound';

    public const URL_LANG_PATTERN = '{lang-prefix}';
}
