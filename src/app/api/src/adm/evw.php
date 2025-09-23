<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once SESTO_DIR . '/app/resource.php';

require_once VAQUITA_DIR . '/event/write.php';

function vaquita_exec(vaquita_api_app $app)
{
  list($filename, $error) = vaquita_event_write($app->config, $app->request->data);
  if ($error !== '') {
    $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
    $app->response->message = 'error saving event';
  } else {
    $app->response->code = SESTO_HTTP_OK;
  }
}
