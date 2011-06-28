<?php
/**
 * @file
 *
 * ARBO content type implementation.
 */

$plugin = array(
  'single' => TRUE,
  'title' => t('ARBO'),
  'description' => t('ARBO widget for recording video reviews.'),
  'required context' => new ctools_context_required(t('Ting object'), 'ting_object'),
  'category' => t('INLEAD'),
);

/**
 * Implements MODULE_CONTENT_content_type_render().
 *
 * Displays ARBO content type.
 */
function ding_arbo_arbo_content_type_render($subtype, $conf, $panel_args, $context) {
  $block = new stdClass();
  $object = isset($context->data) ? $context->data : NULL;
  $block->content = theme('arbo', array('object' => $object));

  return $block;
}

/**
 * Returns an edit form for the content type.
 *
 * We're not actually doing anything here, but we need to have this hook
 * for this content type to work.
 */
function ding_arbo_arbo_content_type_edit_form($form, &$form_state) {
  return $form;
}
