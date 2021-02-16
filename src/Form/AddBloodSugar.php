<?php

namespace Drupal\prescription_management_system\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\node\Entity\Node;

/**
 * Class AddBloodSugar.
 */
class AddBloodSugar extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_blood_sugar';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['bs_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Blood Sugar Value'),
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
    ];
    $form['save_bs_value'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save bs value'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      // @TODO: Validate fields.
      $bs_value = $form_state->getValue('bs_value');
      if(!($bs_value >= 0) && ($bs_value <= 10)) {
        // Set an error if password and confirm password field value doesn't match
        $form_state->setErrorByName('bs_value',$this->t("Blood Sugar value can only be between 0 to 10"));
      }
      parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @TODO: Store blood sugar in blood sugar content type.
      //Get blood sugar value
      $bs_value = $form_state->getValue('bs_value');
      //Get the current logged in user id.
      $uid = \Drupal::currentUser()->id();
      $sl_no = 'Item '.$uid;
      //Store blood sugar value in blood sugar conent type
      $node = Node::create([
        'type'        => 'blood_sugar',
        'title'       => $sl_no,
        'field_ref_user' => [
          'target_id' => $uid
        ],
        'field_blood_sugar' => $bs_value
      ]);
      $node->save();
  }

}
