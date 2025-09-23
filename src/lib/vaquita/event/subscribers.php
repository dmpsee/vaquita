<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_lst.php';

function vaquita_event_subscribers(array $config, string $event): array
{
  return vinti_fi_lst($config['vinti_event_dir'] . '/' . $event . '/sub');
}
