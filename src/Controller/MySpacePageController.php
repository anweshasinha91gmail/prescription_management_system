<?php

namespace Drupal\prescription_management_system\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\views\Views;

/**
 * Class MySpacePageController.
 */
class MySpacePageController extends ControllerBase {

  /**
   * My_space_page.
   *
   * @return object
   *   Return Hello string.
   */
  public function my_space_page() {
    //Get Blood Sugar Form
        $bs_form = $this->formBuilder()->getForm('Drupal\prescription_management_system\Form\AddBloodSugar');
        $renderer = \Drupal::service('renderer');
        $bs_form_html = $renderer->render($bs_form);
    //Get Blood Sugar View
    $view = Views::getView('user_blood_sugar');
    $arg[0] = \Drupal::currentUser()->id();
    if (is_object($view)) {
        $view->setDisplay('block_1');
        $view->setArguments($arg);
        $view->execute();
        // Render the view
        $bs_view = \Drupal::service('renderer')->render($view->render());
    }
    //Get User Prescription Form View
    $u_pres_form = $this->formBuilder()->getForm('Drupal\prescription_management_system\Form\AddPrescription');
        $renderer = \Drupal::service('renderer');
        $u_pres_form_html = $renderer->render($u_pres_form);
    //Get User Prescription view
    $user_prescription_view = Views::getView('user_prescription');
    $user_arg[0] = \Drupal::currentUser()->id();
    if (is_object($user_prescription_view)) {
        $user_prescription_view->setDisplay('block_1');
        $user_prescription_view->setArguments($user_arg);
        $user_prescription_view->execute();
        // Render the view
        $user_pres_view = \Drupal::service('renderer')->render($user_prescription_view->render());
    }
        return [
          '#markup' => Markup::create("
              {$bs_form_html}
              <br>
              {$bs_view}
              <br>
              {$u_pres_form_html}
              <br>
              {$user_pres_view}
          ")
      ];
  }

}
