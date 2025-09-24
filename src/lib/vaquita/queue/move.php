<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_ren.php';

function vaquite_queue_move(array $config, string $event_id, string $from, string $to): array
{
  return vinti_fi_ren(
    $config['vinti_queue_dir'] . '/' . $from, $event_id,
    $config['vinti_queue_dir'] . '/' . $to . '/' . $event_id);
}
