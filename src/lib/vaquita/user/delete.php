<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fo_del.php';

function vaquita_user_delete(array $config, string $id): array
{
  return vinti_fo_del($config['vinti_user_dir'] . '/' . $id);
}