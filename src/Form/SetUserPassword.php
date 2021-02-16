<?php

namespace Drupal\prescription_management_system\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class SetUserPassword.
 */
class SetUserPassword extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'set_user_password';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['confirm_password'] = [
      '#type' => 'password',
      '#title' => $this->t('Confirm Password'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
      // @TODO: Validate fields.
      $user_pass = $form_state->getValue('user_password');
      $confirm_user_pass = $form_state->getValue('confirm_password');
      if($user_pass != $confirm_user_pass) {
        // Set an error if password and confirm password field value doesn't match
        $form_state->setErrorByName('user_password',$this->t("Password and Confirm Password doesn't match"));
      }
      parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @TODO: Store password for user and redirect to dashboard.
      //Get Password
      $pass = $form_state->getValue('user_password');
      //Get the encoded data from the url.
      $encode_value = \Drupal::request()->query->get('encode_data');
      //Base64Decode it.
      $decode_encode_value = base64_decode($encode_value);
      //Explode the decoded vale
      $explode_arr = explode("2021", $decode_encode_value);
      $explode_back_arr = explode("#!email_verify", $explode_arr[1]);
      //Get the ID after the 4 digit randaom number from the string
      $uid = substr($explode_back_arr[0],4);
      //load user details by id
      // Get user storage object.
      $user_storage = \Drupal::entityManager()->getStorage('user');
      $account = $user_storage->load($uid);
      if(is_object($account)) {
            // Set the new password
            $account->setPassword($pass);
            // Save the user
            $account->save();
            // Programatically make the user login
            user_login_finalize($account);
            //Redirect to my-space page
            $response = new RedirectResponse("my-space");
            $response->send();
      }

    
  }

}
