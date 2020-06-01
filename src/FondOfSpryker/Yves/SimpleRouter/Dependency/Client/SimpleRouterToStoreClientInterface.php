<?php

namespace FondOfSpryker\Yves\SimpleRouter\Dependency\Client;

use Generated\Shared\Transfer\StoreTransfer;

interface SimpleRouterToStoreClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;
}
