<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RequestMatcher;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\RequestMatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CrawlerRequestMatcherPlugin
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory()
 */
class CrawlerRequestMatcherPlugin extends AbstractPlugin implements RequestMatcherPluginInterface
{
    /**
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        if ($this->isCrawler()) {
            return ['type' => RouterConstants::RESOURCE_NOT_FOUND_TYPE];
        }

        return [];
    }

    /**
     * @return bool
     */
    protected function isCrawler(): bool
    {
        return $this->getFactory()->createCrawlerDetect()->isCrawler();
    }
}
