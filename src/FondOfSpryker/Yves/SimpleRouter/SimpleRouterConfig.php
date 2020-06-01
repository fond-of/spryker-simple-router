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
        return $this->get(SimpleRouterConstants::YVES_EXCLUDED_ROUTE_PREFIXES, ['/payone' => ['GET'], '/feed' => ['GET'], '/_profiler' => ['GET'], '/form' => ['GET']]);
    }

    /**
     * @return array
     */
    public function getBlacklistedLocale(): array
    {
        return $this->get(SimpleRouterConstants::BLACKLISTED_LOCALE_PREFIXES, []);
    }
}
