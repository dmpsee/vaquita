<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once SESTO_DIR . '/url/init.php';
require_once SESTO_DIR . '/scd/call.php';
require_once SESTO_DIR . '/http/header_json.php';

require_once VAQUITA_DIR . '/api/app.php';
require_once VAQUITA_DIR . '/user/user.php';
require_once VAQUITA_DIR . '/user/read.php';
require_once VAQUITA_DIR . '/api/response_send.php';

function vaquita_api_engine(array $config, array $args = []): void
{
  /* define the $app array */
  $app = new vaquita_api_app();
  $app->config = $config;
  $app->config['vinti_user_dir'] = $app->config['vinti_dir'] . '/user';
  $app->config['vinti_token_dir'] = $app->config['vinti_dir'] . '/key';
  $app->config['vinti_sub_dir'] = $app->config['vinti_dir'] . '/sub';
  $app->config['vinti_url_dir'] = $app->config['vinti_dir'] . '/url';
  $app->config['vinti_event_dir'] = $app->config['vinti_dir'] . '/event';
  $app->config['vinti_queue_dir'] = $app->config['vinti_dir'] . '/queue';

  $app->args = $args;

  $auth_credential = ($_SERVER['HTTP_AC'] ?? '');
  $auth_credential = 'adm-1:key-adm-1'; // admin
  // $auth_credential = 'sub-1:key-sub-1'; // subscriber
  $ac_parts = explode(':', $auth_credential, 2);
  $api_id = $ac_parts[0] ?? '';
  $api_key = $ac_parts[1] ?? '';
  $app->response = new vaquita_api_response();

  $app->url = sesto_url_init();
  try {
    $valid_cmd = [
      'adm' => ['usw', 'usd', 'evw', 'evd', 'eva', 'evi'],
      'pub' => ['evp'],
      'sub' => ['url', 'evs', 'evu']
    ];
    if ($app->url->filename !== 'post') {
      $app->response->code = SESTO_HTTP_NOT_FOUND;
    } else {
      /* request */
      $request_json = file_get_contents('php://input') ?: '';
      $request = json_decode($request_json, true) ?? [];
      if ($request === null) {
        $app->response->code = SESTO_HTTP_BAD_REQUEST;
        $app->response->message = 'invalid json request';
      } else {
        /* set and validate request cmd and data */
        $app->request = new vaquita_api_request();
        $app->request->cmd = $request[0] ?? '';
        $app->request->data = $request[1] ?? '';
        if ($app->request->data === '') {
          $app->response->code = SESTO_HTTP_BAD_REQUEST;
          $app->response->message = 'invalid data element';
        } else {
          /* user */
          list($user_data, $error) = vaquita_user_read($app->config, $api_id);
          if ($error !== '') {
            $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
          } else {
            $user = json_decode($user_data, true);
            if (!is_array($user)) {
              $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
            } else {
              $app->user = new vaquita_user();
              $app->user->id = $user['id'] ?? '';
              $app->user->key = $user['key'] ?? '';
              $app->user->role = $user['role'] ?? '';

              if ($app->user->key !== $api_key) {
                $app->response->code = SESTO_HTTP_UNAUTHORIZED;
              } else {
                /* check valid command */
                if (!in_array($app->request->cmd, $valid_cmd[$app->user->role])) {
                  $app->response->code = SESTO_HTTP_BAD_REQUEST;
                  $app->response->message = 'invalid command';
                } else {
                  /* check controller */
                  $app->controller_dir = SESTO_APP_SRC_DIR;
                  $path_cms_bin = $app->controller_dir . '/' . $app->user->role . '/'. $app->request->cmd . '.php';
                  if (!is_file($path_cms_bin) || !is_readable($path_cms_bin)) {
                    $app->response->code = SESTO_HTTP_NOT_FOUND;
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
  }
  /* response */
  send_reponse($app->response);

}
