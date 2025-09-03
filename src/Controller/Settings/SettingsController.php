<?php

declare(strict_types=1);

namespace Prestashop\Module\Pseproductlisttype\Controller\Settings;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends FrameworkBundleAdminController
{
    public function index(Request $request): Response
    {
        $textFormDataHandler = $this->get('prestashop.module.pseproductlisttype.form.settings_data_handler');

        $textForm = $textFormDataHandler->getForm();
        $textForm->handleRequest($request);

        if ($textForm->isSubmitted() && $textForm->isValid()) {
            /** You can return array of errors in form handler and they can be displayed to user with flashErrors */
            $errors = $textFormDataHandler->save($textForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('pseproductlisttype_settings');
            }

            $this->flashErrors($errors);
        }

        return $this->render('@Modules/pseproductlisttype/views/templates/admin/settings/settingsForm.html.twig', [
            'settingsForm' => $textForm->createView()
        ]);
    }
}
