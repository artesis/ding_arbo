<?php

/**
 * @file
 *
 * Send an upload request and read response status
 */
function arbo_responder() {
  $status = file_get_contents(variable_get('arbo_responder_server_addr', RESPONDER_ADDR) . '?movie_name=' . urlencode($_POST['movie_name']) . '&review_title=' . urlencode($_POST['movie_title']) . '&review_description=' . urlencode($_POST['movie_description']) . '&review_tags=' . urlencode($_POST['movie_tags']) . '&user_email=' . urlencode($_POST['user_email']) . '&faust_number=' . urlencode($_POST['faust_number']) . '&user_id=' . urlencode($_SESSION['voxb']['userId']) . '&ding_id=' . urlencode($_POST['object_id']));

  echo $status;
}
