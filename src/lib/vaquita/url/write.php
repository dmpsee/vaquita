<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_set.php';

function vaquita_url_write(array $config, string $id, string $url): array
{
  return vinti_fi_set($config['vinti_user_dir'] . '/'. $id, 'url', $url);
}