<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/url/read.php';

function vaquita_exec(vaquita_api_app $app)
{
  list($url, $error) = vaquita_url_read($app->config, $app->user->id);
  if ($error !== '') {
    $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
    $app->response->message = 'error saving event';
  } else {
    $app->response->code = SESTO_HTTP_OK;
    $app->response->message = $url;
  }
}
