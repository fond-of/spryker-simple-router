<?php

namespace FondOfSpryker\Yves\SimpleRouter\Dependency\Plugin;

use Symfony\Component\HttpFoundation\Request;

interface RequestMatcherPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function handle(Request $request): array;
}
