<?php

namespace FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin;

use Symfony\Component\HttpFoundation\Request;

interface ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getModuleName(): string;

    /**
     * @return string
     */
    public function getActionName(): string;

    /**
     * @return string
     */
    public function getControllerName(): string;

    /**
     * @return bool
     */
    public function isDefault(): bool;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function modifyRequest(Request $request): void;
}
