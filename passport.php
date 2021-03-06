<?php
require_once(__DIR__.'/common.php');

$errors = array();

if (preg_match('/[0-9]+/', $_SERVER['HTTP_PYORK_CYIN'])) {

  $user = alma_user($_SERVER['HTTP_PYORK_CYIN']);
  $univ_id = user_univ_id($user);

  if ($univ_id == $_SERVER['HTTP_PYORK_CYIN']) {
    $logger->debug("Got matching user record from ALMA with UNIV_ID $univ_id");
    if (check_access($user)) {
      $primary_id = $user->primary_id;
      $logger->debug("User $primary_id / $univ_id allowed access");
      $dest_url = $_REQUEST['qurl'];
      if (is_ebl($dest_url)) {
        $redirect_url = ebl_connect_url($dest_url, $_GET['extendedid'], $_GET['target'], $user->primary_id, $ebl_secret);
        $logger->debug("EBL $redirect_url");
      } else {
        $redirect_url = ezproxy_ticket_login_url($proxy_url, $proxy_secret, $user->primary_id) . '&url=' . $dest_url;
        $logger->debug("EZProxy $redirect_url");
      }
      redirect_terms_of_use($redirect_url);
      exit;
    } else {
      $logger->debug("User $id NOT allowed access");
      $errors[] = "User $id NOT allowed access";
    }
  } else {
      $cyin = $_SERVER['HTTP_PYORK_CYIN'];
      $pyork_user = $_SERVER['HTTP_PYORK_USER'];
      $logger->debug("No user matching UNIV_ID $cyin for $pyork_user");
      $errors[] = "According to our records, there is no library account for $pyork_user.";
  }
} else {
  $logger->debug("Bad CYIN (hint: check mod auth_pyork config): " . $_SERVER['HTTP_PYORK_CYIN']);
  $errors[] = "Server error, invalid CYIN.";
} 

show_error($errors);

?>

