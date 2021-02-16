<?php

namespace Drupal\prescription_management_system\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Class AddPrescription.
 */
class AddPrescription extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_prescription';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['prescription'] = [
        '#type' => 'managed_file',
        '#title' => $this->t('Prescription'),
        '#upload_location' => 'public://',
        '#upload_validators' => [
            'file_validate_extensions' => ['jpg png jpeg'],
        ],
    ];
    $form['description'] = array(
        '#title' => t('Description'),
        '#type' => 'textarea',
    );
        
    $form['save_prescription'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save prescription'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      // @TODO: Validate fields.
      parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @TODO: Store prescription in prescription content type.
      //Get description value
      $description = $form_state->getValue('description');
      //Get the current logged in user id.
      $uid = \Drupal::currentUser()->id();
      //Get prescription id
      $form_file = $form_state->getValue('prescription', 0);
      $sl_no = 'Item '.$uid;
      //Store the values in prescription conent type
      $node = Node::create([
        'type'        => 'prescription',
        'title'       => $sl_no,
        'field_ref_user' => [
          'target_id' => $uid
        ],
        'field_prescription' => [
            'target_id' => $form_file[0]
          ],
      ]);
      $node->save();
  }

}