<?php

use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\HtmlColumn;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShopBundle\Form\Admin\Type\CountryChoiceType;

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require dirname(__FILE__) . '/vendor/autoload.php';
}

class pseproductlisttype extends Module
{
    public function __construct()
    {
        $this->name = 'pseproductlisttype';
        $this->tab = 'others';
        $this->author = 'Pierre Sénéchal';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;

        $this->displayName = $this->trans('Product Type in Product List', [], 'Modules.Pseproductlisttype.Admin');
        $this->description = $this->trans(
            'Displays the product type (standard, pack, combinations, virtual) in the back-office product list for easier management.',
            [],
            'Modules.Pseproductlisttype.Admin'
        );

        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => '8.2.1'];

        parent::__construct();
    }

    public function install(): bool
    {
        Configuration::updateValue('BG_COLOR_SIMPLE_PRODUCT', '#FFC107');
        Configuration::updateValue('BG_COLOR_COMBINATION_PRODUCT', '#28A745');
        Configuration::updateValue('BG_COLOR_PACK_PRODUCT', '#007BFF');
        Configuration::updateValue('BG_COLOR_VIRTUAL_PRODUCT', '#FF5722');

        return parent::install()
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('actionProductGridDefinitionModifier')
            && $this->registerHook('actionProductGridQueryBuilderModifier');
    }

    public function uninstall(): bool
    {
        Configuration::deleteByName('BG_COLOR_SIMPLE_PRODUCT');
        Configuration::deleteByName('BG_COLOR_COMBINATION_PRODUCT');
        Configuration::deleteByName('BG_COLOR_PACK_PRODUCT');
        Configuration::deleteByName('BG_COLOR_VIRTUAL_PRODUCT');

        return parent::uninstall()
            && $this->unregisterHook('actionAdminControllerSetMedia')
            && $this->unregisterHook('actionProductGridDefinitionModifier')
            && $this->unregisterHook('actionProductGridQueryBuilderModifier');
    }

    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    public function getContent(): void
    {
        $route = $this->get('router')->generate('pseproductlisttype_settings');
        Tools::redirectAdmin($route);
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addJS($this->_path . '/views/js/back.js');
        $this->context->controller->addCSS($this->_path . '/views/css/back.css');
    }

    public function hookActionProductGridDefinitionModifier(array $params): void
    {
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];
        $activePackProduct = Configuration::get('ACTIVE_VIRTUAL_PRODUCT');

        $choices = [
            $this->trans('Simple', [], 'Modules.Pseproductlisttype.Admin') => 'simple',
            $this->trans('Combination', [], 'Modules.Pseproductlisttype.Admin') => 'combination',
            $this->trans('Pack', [], 'Modules.Pseproductlisttype.Admin') => 'pack',
        ];

        if ($activePackProduct) {
            $choices[$this->trans('Virtual', [], 'Modules.Pseproductlisttype.Admin')] = 'virtual';
        }

        $definition->getColumns()->addAfter(
            'name',
            (new HtmlColumn('type_product_badge'))
                ->setName($this->trans('Product type', [], 'Modules.Pseproductlisttype.Admin'))
                ->setOptions([
                    'field' => 'type_product_badge',
                ])
        );

        $definition->getFilters()->add(
            (new Filter('type_product', CountryChoiceType::class))
                ->setTypeOptions([
                    'choices' => $choices,
                    'placeholder' => $this->trans('All', [], 'Admin.Global'),
                    'required' => false,
                ])
                ->setAssociatedColumn('type_product_badge')
        );

    }

    public function hookActionProductGridQueryBuilderModifier(array $params): void
    {
        /** @var \Doctrine\DBAL\Query\QueryBuilder $searchQueryBuilder */
        $searchQueryBuilder = $params['search_query_builder'];
        $searchCriteria = $params['search_criteria'];
        $activeVirtualProduct = (bool) Configuration::get('ACTIVE_VIRTUAL_PRODUCT');

        $caseType = '
        CASE
            ' . ($activeVirtualProduct ? 'WHEN p.is_virtual = 1 THEN "virtual"' : '') . '
            WHEN pk.id_product_pack IS NOT NULL THEN "pack"
            WHEN pa.id_product_attribute IS NOT NULL THEN "combination"
            ELSE "simple"
        END AS type_product
    ';
        $searchQueryBuilder->addSelect($caseType);

        $caseBadge = '
        CASE
            ' . ($activeVirtualProduct ? 'WHEN p.is_virtual = 1 THEN "' . $this->quoteBadge(
                    $this->trans('Virtual', [], 'Modules.Pseproductlisttype.Admin'),
                    Configuration::get('BG_COLOR_VIRTUAL_PRODUCT')
                ) . '"' : '') . '
            WHEN pk.id_product_pack IS NOT NULL THEN "' . $this->quoteBadge(
                $this->trans('Pack', [], 'Modules.Pseproductlisttype.Admin'),
                Configuration::get('BG_COLOR_PACK_PRODUCT')
            ) . '"
            WHEN pa.id_product_attribute IS NOT NULL THEN "' . $this->quoteBadge(
                $this->trans('Combination', [], 'Modules.Pseproductlisttype.Admin'),
                Configuration::get('BG_COLOR_COMBINATION_PRODUCT')
            ) . '"
            ELSE "' . $this->quoteBadge(
                $this->trans('Simple', [], 'Modules.Pseproductlisttype.Admin'),
                Configuration::get('BG_COLOR_SIMPLE_PRODUCT')
            ) . '"
        END AS type_product_badge
    ';
        $searchQueryBuilder->addSelect($caseBadge);

        $searchQueryBuilder->leftJoin(
            'p',
            _DB_PREFIX_ . 'product_attribute',
            'pa',
            'pa.id_product = p.id_product'
        );

        $searchQueryBuilder->leftJoin(
            'p',
            _DB_PREFIX_ . 'pack',
            'pk',
            'pk.id_product_pack = p.id_product'
        );

        $searchQueryBuilder->groupBy('p.id_product');

        $filters = $searchCriteria->getFilters();
        if (array_key_exists('type_product', $filters) && $filters['type_product'] !== '' && $filters['type_product'] !== null) {
            $searchQueryBuilder->andHaving('type_product = :type_product');
            $searchQueryBuilder->setParameter('type_product', $filters['type_product']);
        }
    }


    private function quoteBadge(string $label, $hexColor): string
    {
        $hexColor = ltrim($hexColor, '#');

        if (strlen($hexColor) === 3) {
            $r = hexdec(str_repeat(substr($hexColor, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hexColor, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hexColor, 2, 1), 2));
        } else {
            $r = hexdec(substr($hexColor, 0, 2));
            $g = hexdec(substr($hexColor, 2, 2));
            $b = hexdec(substr($hexColor, 4, 2));
        }

        $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;

        $textColor = $brightness < 128 ? '#FFFFFF' : '#000000';

        $bgColor = "rgba({$r}, {$g}, {$b}, 0.5)";
        $borderColor = "rgb({$r}, {$g}, {$b})";

        return "<span class='badge' style='background: {$bgColor}; border: 1px solid {$borderColor}; color: {$textColor};'>{$label}</span>";
    }
}
