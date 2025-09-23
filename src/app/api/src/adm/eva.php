<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/event/exists.php';
require_once VAQUITA_DIR . '/event/allow.php';

function vaquita_exec(vaquita_api_app $app)
{
  if (!isset($app->request->data[0]) || !isset($app->request->data[1])) {
    $app->response->code = SESTO_HTTP_BAD_REQUEST;
  } else if ($app->request->data[0] === '' || $app->request->data[1] === '') {
    $app->response->code = SESTO_HTTP_BAD_REQUEST;
  } else {
    if (!vaquita_event_exists($app->config, $app->request->data[0])) {
      $app->response->code = SESTO_HTTP_BAD_REQUEST;
      $app->response->message = 'invalid event';
    } else {
      list($filename, $error) = vaquita_event_allow($app->config, $app->request->data[0], $app->request->data[1]);
      if ($error !== '') {
        $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
        $app->response->message = 'error saving event';
      } else {
        $app->response->code = SESTO_HTTP_OK;
      }
    }
  }
}
