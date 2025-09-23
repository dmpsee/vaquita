<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VAQUITA_DIR . '/api/app.php';
require_once VAQUITA_DIR . '/event/dir.php';

function vaquita_event_read(string $event, vaquita_api_app $app): array
{
  return vinti_fi_get(vaquita_event_dir($event, $app), '_' . $event);
}
