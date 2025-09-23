<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_get.php';

function vaquita_user_read(array $config, string $id): array
{
  return vinti_fi_get($config['vinti_user_dir'] . '/' . $id, 'user');
}
