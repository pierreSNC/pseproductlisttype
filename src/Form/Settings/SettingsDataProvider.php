<?php

declare(strict_types=1);

namespace Prestashop\Module\Pseproductlisttype\Form\Settings;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

/**
 * Provider is responsible for providing form data, in this case, it is returned from the configuration component.
 *
 * Class DemoConfigurationTextFormDataProvider
 */
class SettingsDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $settingsDataConfiguration;

    public function __construct(DataConfigurationInterface $settingsDataConfiguration)
    {
        $this->settingsDataConfiguration = $settingsDataConfiguration;
    }

    public function getData(): array
    {
        return $this->settingsDataConfiguration->getConfiguration();
    }

    public function setData(array $data): array
    {
        return $this->settingsDataConfiguration->updateConfiguration($data);
    }
}
