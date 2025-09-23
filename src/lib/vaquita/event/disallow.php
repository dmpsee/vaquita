<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_del.php';

function vaquita_event_disallow(array $config, string $event, string $user_id): array
{
  return vinti_fi_del($config['vinti_event_dir'] . '/' . $event . '/pub', $user_id);
}
