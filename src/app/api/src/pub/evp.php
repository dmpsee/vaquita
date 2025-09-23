<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/queue/insert.php';

function vaquita_exec(vaquita_api_app $app)
{
  if ($app->user->role !== 'pub') {
    $app->response->code = SESTO_HTTP_UNAUTHORIZED;
    $app->response->message = 'invalid role';
    return;
  }
  list($filename, $error) = vaquita_queue_insert($app->config, json_encode($app->request->data));
  if ($error !== '') {
    $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
    $app->response->message = 'error saving event';
  } else {
    $app->response->code = SESTO_HTTP_OK;
    $app->response->message = $filename;
  }
}
