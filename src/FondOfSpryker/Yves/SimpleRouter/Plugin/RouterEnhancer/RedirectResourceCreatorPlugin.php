<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\RouterEnhancer;

use FondOfSpryker\Shared\SimpleRouter\SimpleRouterConstants;
use FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin\ResourceCreatorPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class RedirectResourceCreatorPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @var bool
     */
    protected $isDefault;

    /**
     * @param bool $isDefault
     */
    public function __construct(bool $isDefault = false)
    {
        $this->isDefault = $isDefault;
    }

    public function getType(): string
    {
        return SimpleRouterConstants::REDIRECT_TYPE;
    }

    public function getModuleName(): string
    {
        return 'RedirectPage';
    }

    public function getActionName(): string
    {
        return 'redirect';
    }

    public function getControllerName(): string
    {
        return 'Redirect';
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function modifyRequest(Request $request): void
    {
    }
}
