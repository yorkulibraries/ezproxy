<?php
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/vendor/autoload.php');

session_start();

// setup logger
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

$logger = new Logger('logger');
$formatter = new LineFormatter(
    null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
    null, // Datetime format
    true, // allowInlineLineBreaks option, default false
    true  // discard empty Square brackets in the end, default false
);
$handler = new StreamHandler(__DIR__.'/log/messages.log', Logger::INFO);
$handler->setFormatter($formatter);
$logger->pushHandler($handler);

if ($enable_debug_log) {
  $handler = new StreamHandler(__DIR__.'/log/debug.log', Logger::DEBUG);
  $handler->setFormatter($formatter);
  $logger->pushHandler($handler);
}

function user_univ_id($user) {
  $identifiers = $user->user_identifier;
  foreach ($identifiers as $i) {
    if ($i->id_type->value == 'UNIV_ID') {
      return $i->value;
    }
  }
  return null;
}

function alma_user($id) {
  global $api_key;
  global $api_url;
 
  $url = $api_url . '/users/' . $id;
  $params = array(
    'user_id_type' => 'all_unique',
    'view' => 'full',
    'expand' => 'none',
    'apikey' => $api_key
  );
  $url = $url . "?" . http_build_query($params);
   
  $headers = [
    'Accept: application/json'
  ];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
  curl_close($ch);

  $user = json_decode($response);
  return $user;
}

function is_york_patron($user) {
  foreach ($user->user_role as $r) {
    if ($r->status->value == 'ACTIVE' && $r->scope->value == '01OCUL_YOR' && $r->role_type->desc == 'Patron') {
      return true;
    }
  }
  return false;
}

function check_access($user) {
  global $allowed_user_groups;
  global $allowed_user_categories;

  $group = $user->user_group->value;
  $status = $user->status->value;
  $expiry = strtotime($user->expiry_date);
  $blocks = $user->user_block;

  // check if user is allowed
  if ($status == 'ACTIVE'
    && $expiry !== false
    && empty($blocks)
    && $expiry > time()
    && in_array($group, $allowed_user_groups)
  ) {
    return true;
  }
}

function ezproxy_ticket_login_url($server, $secret, $user, $groups = "") {
  $packet = '$u' . time();
  if (strcmp($groups, "") != 0) {
    $packet .=  '$g' . $groups;
  }
  $packet .= '$e';
  $ticket = urlencode(md5($secret . $user . $packet) . $packet);
  $url = $server . "/login?user=" . urlencode($user) . "&ticket=" . $ticket;
  return $url;
}

function verify_captcha($secret, $response, $ip) {
  global $logger;
  $verify_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' 
    . $secret . '&response=' . $response . "&remoteip=". $ip;
  $logger->debug($verify_url);
  $json = file_get_contents($verify_url);
  return json_decode($json);      
}

function show_form($recaptcha_site_key, $errors) {
  include(__DIR__.'/form.php');
  exit;
}

function redirect_terms_of_use($url) {
  $location = 'tou?dest=' . urlencode($url);
  header("Location: $location");
  exit;
}


function authenticate($id, $pass) {
  global $api_key;
  global $api_url;

  $url = $api_url . '/users/' . $id;
  $params = array(
    'user_id_type' => 'all_unique',
    'apikey' => $api_key,
    'op' => 'auth',
    'password' => $pass,
  );
  $url = $url . "?" . http_build_query($params);

  $headers = [
    'Accept: application/json'
  ];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLINFO_HEADER_OUT, true);
  curl_setopt($ch, CURLOPT_POST, true);

  // Set HTTP Header for POST request 
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json',
  ));

  $response = curl_exec($ch);

  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  curl_close($ch);

  return array($code, $response);
}


?>

