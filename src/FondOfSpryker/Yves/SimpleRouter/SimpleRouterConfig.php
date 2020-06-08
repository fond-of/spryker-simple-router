<?php

namespace FondOfSpryker\Yves\SimpleRouter;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class SimpleRouterConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getExcludedRoutePrefixes(): array
    {
        return $this->get(SimpleRouterConstants::YVES_EXCLUDED_ROUTE_PREFIXES,
            ['/payone' => ['GET', 'POST'], '/error' => ['GET'], '/feed' => ['GET'], '/_profiler' => ['GET'], '/form' => ['GET', 'POST']]);
    }

    /**
     * @return array
     */
    public function getBlacklistedLocale(): array
    {
        return $this->get(SimpleRouterConstants::BLACKLISTED_LOCALE_PREFIXES, []);
    }

    /**
     * @return bool
     */
    public function redirectCrawler(): bool
    {
        return $this->get(SimpleRouterConstants::SHOULD_REDIRECT_CRAWLER, false);
    }

    public function isRedirectAllowed(){

    }
}
