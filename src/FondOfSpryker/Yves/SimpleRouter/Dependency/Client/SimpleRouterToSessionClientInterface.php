<?php

namespace FondOfSpryker\Yves\SimpleRouter\Dependency\Client;

interface SimpleRouterToSessionClientInterface
{
    /**
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $name, $value): void;
}
