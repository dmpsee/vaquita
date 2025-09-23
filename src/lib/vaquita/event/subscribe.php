<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_set.php';

function vaquita_event_subscribe(array $config, string $event, vaquita_user $user): array
{
  return vinti_fi_set($config['vinti_event_dir'] . '/' . $event . '/sub', $user->id, $event);
}
