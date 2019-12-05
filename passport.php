<?php
require_once(__DIR__.'/common.php');

if (preg_match('/[0-9]+/', $_SERVER['HTTP_PYORK_CYIN'])) {

  $user = alma_user($_SERVER['HTTP_PYORK_CYIN']);
  $univ_id = user_univ_id($user);

  if ($univ_id == $_SERVER['HTTP_PYORK_CYIN']) {
    $logger->debug("Got matching user record from ALMA with UNIV_ID $univ_id");
    if (check_access($user)) {
      $logger->debug("User $univ_id allowed access");
      $dest_url = $_REQUEST['qurl'];
      $redirect_url = ezproxy_ticket_login_url($proxy_url, $proxy_secret, $user->primary_id) . '&url=' . $dest_url;
      redirect_terms_of_use($redirect_url);
      exit;
    } else {
      $logger->debug("User $id NOT allowed access");
    }
  }
} else {
  $logger->debug("Bad CYIN (hint: check mod auth_pyork config): " . $_SERVER['HTTP_PYORK_CYIN']);
} 


?>

