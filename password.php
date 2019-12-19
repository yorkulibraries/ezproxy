<?php
require_once(__DIR__.'/common.php');

$errors = array();
if (isset($_POST['barcode']) && !empty($_POST['barcode']) 
&& isset($_POST['pin']) && !empty($_POST['pin'])
) { 
  $pin = $_POST['pin'];
  $id = $_POST['barcode'];
  $data = verify_captcha($recaptcha_secret_key, $_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']); 
  if ($data->success) { 
    $logger->debug("recaptcha success");
    $auth = authenticate($id, $pin);
     
    if ($auth[0] == 204) {
      $logger->debug("Authenticated user $id");
      $user = alma_user($id);
      if ($user->primary_id == $id) {
        $is_york_patron = is_york_patron($user);
        if ($is_york_patron && check_access($user)) {
          $logger->debug("User $id allowed access");
          $dest_url = $_REQUEST['qurl'];
          $redirect_url = ezproxy_ticket_login_url($proxy_url, $proxy_secret, $user->primary_id) . '&url=' . $dest_url;
          redirect_terms_of_use($redirect_url);
          exit;
        } else {
          $logger->debug("User $id NOT allowed access");
          if (!$is_york_patron) { 
            $logger->debug("User $id does NOT have Patron role");
          }
          $errors[] = 'Access not allowed.';
        }
      } 
    } else {
      $logger->debug("Authentication failed: $pin is wrong for user $id");
      $errors[] = 'Invalid Barcode and/or Password. If you have not reset your password, please enter your existing 4-digit PIN *twice*';
    }
  } else { 
    $logger->debug("recaptcha failed");
    $errors[] = 'reCAPTCHA failed';
  }
} else {
  $errors[] = 'Please enter barcode and password to sign in.';
}

show_form($recaptcha_site_key, $errors);
?>
