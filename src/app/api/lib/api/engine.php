<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

use function sesto_log_file as slog;

require_once SESTO_DIR . '/log/file.php';
require_once SESTO_DIR . '/url/init.php';
require_once SESTO_DIR . '/scd/call.php';
require_once SESTO_DIR . '/http/post.php';
require_once VAQUITA_API_DIR . '/api.php';
require_once VAQUITA_DIR . '/user/user.php';
require_once VAQUITA_DIR . '/user/read.php';
require_once VAQUITA_DIR . '/user/exists.php';

function vaquita_api_engine(array $config, array $args = []): void
{
  /* define the $app array */
  $app = new vaquita_api();
  $app->config = $config;
  $app->config['vinti_user_dir'] = $app->config['vinti_dir'] . '/user';
  $app->config['vinti_token_dir'] = $app->config['vinti_dir'] . '/key';
  $app->config['vinti_sub_dir'] = $app->config['vinti_dir'] . '/sub';
  $app->config['vinti_url_dir'] = $app->config['vinti_dir'] . '/url';
  $app->config['vinti_event_dir'] = $app->config['vinti_dir'] . '/event';
  $app->config['vinti_queue_dir'] = $app->config['vinti_dir'] . '/queue';

  $app->args = $args;

  /* log */
  sesto_log_filedata(
    $config['log_filename'] ?? '/var/log/vaquita/api.log',
    $config['log_flags'] ?? FILE_APPEND | LOCK_EX);
  sesto_log_level($config['log_priority'] ?? LOG_INFO);

  $auth_credential = ($_SERVER['HTTP_AC'] ?? '');
  // $auth_credential = 'adm-1:key-adm-1'; // admin
  // $auth_credential = 'sub-1:key-sub-1'; // subscriber
  $ac_parts = explode(':', $auth_credential, 2);
  $api_id = $ac_parts[0] ?? '';
  $api_key = $ac_parts[1] ?? '';
  $app->response = new vaquita_api_response();
  $app->user = new vaquita_user();
  $app->request = new vaquita_api_request();

  $app->url = sesto_url_init();

  $log_message = '';
  $log_context = [
    $_SERVER['REMOTE_ADDR'],
    '', // status_code
    '', // user
    '', // role
    '', // cmd
  ];

  $valid_cmd = [
    'adm' => ['usw', 'usd', 'evw', 'evd', 'eva', 'evi'],
    'pub' => ['evp'],
    'sub' => ['urr', 'urw', 'evs', 'evu']
  ];

  try {
    if (!sesto_http_post()) {
      $app->response->code = SESTO_HTTP_METHOD_NOT_ALLOWED;
    } else if ($app->url->filename !== 'post') {
      $app->response->code = SESTO_HTTP_NOT_FOUND;
    } else if ($api_id === '') {
      $app->response->code = SESTO_HTTP_BAD_REQUEST;
      $log_message = 'empty api_id';
    } else if ($api_key === '') {
      $app->response->code = SESTO_HTTP_BAD_REQUEST;
      $log_message = 'empty api_key';
    } else if (!vaquita_user_exists($app->config, $api_id)) {
      $app->response->code = SESTO_HTTP_UNAUTHORIZED;
      $log_message = 'user not found';
    } else {
      $request = json_decode(file_get_contents('php://input') ?: '', true);
      if ($request === null) {
        $app->response->code = SESTO_HTTP_BAD_REQUEST;
        $log_message = 'invalid json request';
      } else {
        /* set and validate request cmd and data */
        $app->request->cmd = $request[0] ?? '';
        $app->request->data = $request[1] ?? '';
        if ($app->request->data === '' && $app->request->cmd !== 'urr') {
          $app->response->code = SESTO_HTTP_UNPROCESSABLE_CONTENT;
          $log_message = 'invalid request->data';
        } else {
          /* user */
          list($user_data, $error) = vaquita_user_read($app->config, $api_id);
          if ($error !== '') {
            $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
            $log_message = $error;
          } else {
            $user = json_decode($user_data, true);
            if (!is_array($user)) {
              $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
              $log_message = 'invalid json user data';
            } else {
              $app->user->id = $user['id'] ?? '';
              $app->user->key = $user['key'] ?? '';
              $app->user->role = $user['role'] ?? '';
              // sesto_d($app);
              if ($app->user->key !== $api_key) {
                $app->response->code = SESTO_HTTP_UNAUTHORIZED;
                $log_message = 'invalid credentials';
              } else {
                /* check valid command */
                if (!in_array($app->request->cmd, $valid_cmd[$app->user->role])) {
                  $app->response->code = SESTO_HTTP_FORBIDDEN;
                  $log_message = 'not authorised';
                } else {
                  /* check controller */
                  $app->controller_dir = SESTO_APP_SRC_DIR;
                  $path_cms_bin = $app->controller_dir . '/' . $app->user->role . '/'. $app->request->cmd . '.php';
                  if (!is_file($path_cms_bin) || !is_readable($path_cms_bin)) {
                    $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
                    $log_message = 'controller not found';
                  } else {
                    /* exec controller */
                    sesto_scd_call(new sesto_scd('vaquita_exec', [], $path_cms_bin), $app);
                  }
                }
              }
            }
          }
        }
      }
    }
  } catch (throwable $ex) {
    $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
    $log_message = $ex->getmessage();
  }

  /* response */
  if (empty($app->response->message)) {
    unset($app->response->message);
  }

  if (empty($app->response->events)) {
    unset($app->response->events);
  }

  header("Status: " . $app->response->code, true);
  header('Content-Type: application/json', true);
  $log_context[1] = $app->response->code;
  $log_context[2] = $api_id ?: '';
  $log_context[3] = $app->user->role ?: '';
  $log_context[4] = $app->request->cmd ?: '';
  $log_priority = match($app->response->code) {
    SESTO_HTTP_INTERNAL_SERVER_ERROR => LOG_ERR,
    default => LOG_INFO,
  };
  unset($app->response->code);
  echo json_encode($app->response, JSON_FORCE_OBJECT);
  slog($log_message, $log_priority, $log_context);
}
