<?php

/**
 * @file
 *
 * Callback function to supply a list of content types.
 */
function arbo_arbo_content_ctools_content_types() {
  return array(
    'single' => TRUE,
    'title' => t('ARBO Content Pane'),
    'icon' => 'icon_user.png',
    'description' => t('Show ARBO video reviews'),
    //'required context' => new ctools_context_required(t('User'), 'user'),
    'category' => t('ARBO'),
    'defaults' => array(),
  );
}

/**
 * Output function for the 'arbo_content' content type.
 */
function arbo_arbo_content_content_type_render($subtype, $conf, $panel_args, $context) {
  $block = new stdClass();

  // @todo Please change this theme call to use an associative array for the $variables parameter.
  $block->content = theme('arbo_widget', $conf);

  return $block;
}
