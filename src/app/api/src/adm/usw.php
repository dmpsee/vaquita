<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/user/write.php';

function vaquita_exec(vaquita_api_app $app)
{
  $user = new vaquita_user();
  $user->id = $app->request->data[0];
  $user->key = $app->request->data[1];
  $user->role = $app->request->data[2];
  list($filename, $error) = vaquita_user_write($app->config, $user);
  if ($error !== '') {
    $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
    $app->response->message = 'error saving event';
  } else {
    $app->response->code = SESTO_HTTP_OK;
  }
}
