<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_has.php';

function vaquita_user_exists(array $config, string $user_id): bool
{
  return vinti_fi_has($config['vinti_user_dir'] . '/'. $user_id, 'user')[0];
}
