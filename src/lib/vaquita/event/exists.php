<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_has.php';

function vaquita_event_exists(array $config, string $event): bool
{
  return vinti_fi_has($config['vinti_event_dir'], $event)[0];
}
