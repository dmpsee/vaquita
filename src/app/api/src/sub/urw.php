<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/url/write.php';

function vaquita_exec(vaquita_api_app $app)
{
  // die('123');
  list($filename, $error) = vaquita_url_write($app->config, $app->user->id, $app->request->data);
  if ($error !== '') {
    $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
    $app->response->message = 'error saving event';
  } else {
    $app->response->code = SESTO_HTTP_OK;
  }
}
