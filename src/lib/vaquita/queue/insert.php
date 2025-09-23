<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/da_ins.php';

function vaquita_queue_insert(array $config, string $data): array
{
  return vinti_da_ins($config['vinti_queue_dir'] . '/new', $data);
}
