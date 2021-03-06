<?php
/**
 * @file
 * Contains \Drupal\distributor_form\Form\DistributorForm;
 */

namespace Drupal\distributor_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\block\Entity\Block;
use Drupal\distributor_form\Plugin\Block\DistributorFormBlock;

class DistributorForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'find_distributor_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['zip_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Location'),
      '#description' => $this->t('Enter your city, state, or postal code'),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // If you want to validate your fields please add here.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $distributor_block = Block::load('distributorform');
    if (empty($distributor_block)) {
      return FALSE;
    }

    $redirect_page_id = $distributor_block->get('settings')['page_id'];
    // Redirect form into distributor form.
    $zip_code = $form_state->getValue('zip_code');
    $zip_with_alphanumeric = preg_replace('/[^a-zA-Z0-9 ]/', '', $zip_code);
    $form_state->setRedirect('entity.node.canonical', [
        'node' => $redirect_page_id
      ], [
        'query' => [
          'locate' => $zip_with_alphanumeric
        ]
      ]
    );
    return;
  }
}
