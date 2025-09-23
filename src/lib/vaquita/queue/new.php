<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fi_lst.php';

function vaquite_queue_new(array $config): array
{
  return vinti_fi_lst($config['vinti_queue_dir'] . '/new');
}
