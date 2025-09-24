<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/event/exists.php';
require_once VAQUITA_DIR . '/event/subscribe.php';

function vaquita_exec(vaquita_api $app)
{
  if (!vaquita_event_exists($app->config, $app->request->data)) {
    $app->response->code = SESTO_HTTP_BAD_REQUEST;
    $app->response->message = 'invalid event';
  } else {
    list($filename, $error) = vaquita_event_subscribe($app->config, $app->request->data, $app->user);
    if ($error !== '') {
      $app->response->code = SESTO_HTTP_INTERNAL_SERVER_ERROR;
      $app->response->message = 'error saving event';
    } else {
      $app->response->code = SESTO_HTTP_OK;
    }
  }
}
