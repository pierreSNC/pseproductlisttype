<?php
declare(strict_types=1);

namespace Prestashop\Module\Pseproductlisttype\Form\Settings;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

/**
 * Configuration is used to save data to configuration table and retrieve from it.
 */
final class SettingsDataConfiguration implements DataConfigurationInterface
{
    public const BG_COLOR_SIMPLE_PRODUCT = 'BG_COLOR_SIMPLE_PRODUCT';
    public const BG_COLOR_COMBINATION_PRODUCT = 'BG_COLOR_COMBINATION_PRODUCT';
    public const BG_COLOR_PACK_PRODUCT = 'BG_COLOR_PACK_PRODUCT';
    public const BG_COLOR_VIRTUAL_PRODUCT = 'BG_COLOR_VIRTUAL_PRODUCT';
    public const ACTIVE_VIRTUAL_PRODUCT = 'ACTIVE_VIRTUAL_PRODUCT';

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        $return = [];

        $return['bg_color_simple_product'] = $this->configuration->get(static::BG_COLOR_SIMPLE_PRODUCT);
        $return['bg_color_combination_product'] = $this->configuration->get(static::BG_COLOR_COMBINATION_PRODUCT);
        $return['bg_color_pack_product'] = $this->configuration->get(static::BG_COLOR_PACK_PRODUCT);
        $return['bg_color_virtual_product'] = $this->configuration->get(static::BG_COLOR_VIRTUAL_PRODUCT);
        $return['active_virtual_product'] = $this->configuration->get(static::ACTIVE_VIRTUAL_PRODUCT);

        return $return;
    }

    public function updateConfiguration(array $configuration): array
    {
        $this->configuration->set(static::BG_COLOR_SIMPLE_PRODUCT, $configuration['bg_color_simple_product']);
        $this->configuration->set(static::BG_COLOR_COMBINATION_PRODUCT, $configuration['bg_color_combination_product']);
        $this->configuration->set(static::BG_COLOR_PACK_PRODUCT, $configuration['bg_color_pack_product']);
        $this->configuration->set(static::BG_COLOR_VIRTUAL_PRODUCT, $configuration['bg_color_virtual_product']);
        $this->configuration->set(static::ACTIVE_VIRTUAL_PRODUCT, $configuration['active_virtual_product']);

        return [];
    }

    /**
     * Ensure the parameters passed are valid.
     *
     * @return bool Returns true if no exception are thrown
     */
    public function validateConfiguration(array $configuration): bool
    {
        return true;
    }
}
