<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/api/response.php';
require_once SESTO_DIR . '/http/status_codes.php';

function send_reponse(vaquita_api_response $response)
{
  foreach (sesto_http_header_json() as $header) {
    header($header);
  }
  /* message */
  if($response->message === '_std_') {
    $response->message = sesto_http_status_codes()[$response->code] ?? '';
  }
  if (empty($response->message)) {
    unset($response->message);
  }

  /* events */
  if (empty($response->events)) {
    unset($response->events);
  }

  header("Status: " . $response->code, true);
  unset($response->code);

  echo json_encode($response, JSON_FORCE_OBJECT);
}
