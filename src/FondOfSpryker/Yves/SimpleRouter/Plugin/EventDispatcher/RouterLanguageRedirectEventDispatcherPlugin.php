<?php

namespace FondOfSpryker\Yves\SimpleRouter\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RouterLanguageRedirectEventDispatcherPlugin
 *
 * @package FondOfSpryker\Yves\SimpleRouter\Plugin\EventDispatcher
 * @method \FondOfSpryker\Yves\SimpleRouter\SimpleRouterFactory getFactory();
 */
class RouterLanguageRedirectEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var string
     */
    protected const USER_DEFAULT_LOCALE_PREFIX = 'USER_DEFAULT_LOCALE_PREFIX';

    /**
     * {@inheritDoc}
     * - Adds event listener that set the locale to the router context.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(
        EventDispatcherInterface $eventDispatcher,
        ContainerInterface $container
    ): EventDispatcherInterface {
        $eventDispatcher = $this->addListeners($eventDispatcher, $container);

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function addListeners(
        EventDispatcherInterface $eventDispatcher,
        ContainerInterface $container
    ): EventDispatcherInterface {
        $eventDispatcher->addListener(KernelEvents::REQUEST, function (RequestEvent $event): void {
            $request = $event->getRequest();
            if ($request->getPathInfo() === '/' && $this->getFactory()->createRedirectValidator()->isLanguageRedirectAllowed() === true) {
                $event->setResponse($this->createLanguageRedirectResponse($event));
            }
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createLanguageRedirectResponse(RequestEvent $event): RedirectResponse
    {
        $queryString = $this->getQueryString($event->getRequest()->getQueryString());
        $target = sprintf('/%s/%s', $this->getUriLocale(), $queryString);
        
        return new RedirectResponse($target, 301);
    }

    /**
     * @param string $queryString
     *
     * @return string
     */
    protected function getQueryString(string $queryString): string
    {
        if (strlen($queryString) > 0) {
            return sprintf('?%s', $queryString);
        }

        return '';
    }

    /**
     * @param string $defaultLocale
     *
     * @return string
     */
    protected function getUriLocale(string $defaultLocale = 'en'): string
    {
        $browserLocale = $this->getUserDefaultLocalePrefix() ?? $this->detectBrowserLocale();

        if ($this->isLocaleAvailableInCurrentStore($browserLocale)) {
            return $browserLocale;
        }

        return $defaultLocale;
    }

    /**
     * @param string $locale
     *
     * @return bool
     */
    protected function isLocaleAvailableInCurrentStore(string $locale): bool
    {
        return array_key_exists(
            $locale,
            $this->getFactory()->getStoreClient()->getCurrentStore()->getAvailableLocaleIsoCodes(),
        );
    }

    /**
     * @return string|null
     */
    protected function detectBrowserLocale(): ?string
    {
        return $this->getFactory()->createBrowserDetectorLanguage()->getLanguage();
    }

    /**
     * @return string|null
     */
    protected function getUserDefaultLocalePrefix(): ?string
    {
        return $this->getFactory()->getSessionClient()->get(self::USER_DEFAULT_LOCALE_PREFIX);
    }
}
