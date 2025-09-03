<?php

declare(strict_types=1);

namespace Prestashop\Module\Pseproductlisttype\Form\Settings;

use PrestaShopBundle\Form\Admin\Type\ColorPickerType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;

class SettingsType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bg_color_simple_product', ColorPickerType::class, [
                'label' => $this->trans('Simple product', 'Modules.Pseproductlisttype.Admin'),
                'required' => false,
            ])
            ->add('bg_color_combination_product', ColorPickerType::class, [
                'label' => $this->trans('Combination product', 'Modules.Pseproductlisttype.Admin'),
                'required' => false,
            ])
            ->add('bg_color_pack_product', ColorPickerType::class, [
                'label' => $this->trans('Pack product', 'Modules.Pseproductlisttype.Admin'),
                'required' => false,
            ])
            ->add('bg_color_virtual_product', ColorPickerType::class, [
                'label' => $this->trans('Pack product', 'Modules.Pseproductlisttype.Admin'),
                'required' => false,
            ])
        ;
    }
}
