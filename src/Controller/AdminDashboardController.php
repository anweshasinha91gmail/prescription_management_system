<?php

namespace Drupal\prescription_management_system\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\views\Views;

/**
 * Class AdminDashboardController.
 */
class AdminDashboardController extends ControllerBase {

  /**
   * Admin_dashboard_page.
   *
   * @return string
   *   Return Hello string.
   */
  public function admin_dashboard_page() {
    //Get Blood Sugar Record View
    $view_bs_record = Views::getView('blood_sugar_record');
    if (is_object($view_bs_record)) {
      $view_bs_record->setDisplay('block_1');
      $view_bs_record->execute();
      // Render the view
      $bs_record_view = \Drupal::service('renderer')->render($view_bs_record->render());
    }
    //Get Prescription Record View
    $view_pres_record = Views::getView('prescription_record');
    if (is_object($view_pres_record)) {
      $view_pres_record->setDisplay('block_1');
      $view_pres_record->execute();
      // Render the view
      $pres_record_view = \Drupal::service('renderer')->render($view_pres_record->render());
    }
    return [
      '#markup' => Markup::create("
          {$bs_record_view}
          <br>
          {$pres_record_view}
      ")
  ];
  }

}
