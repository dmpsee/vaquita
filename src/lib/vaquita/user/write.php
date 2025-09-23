<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fo_set.php';
require_once VINTI_DIR . '/fi_set.php';

function vaquita_user_write(array $config, vaquita_user $user): array
{
  $user_dir = $config['vinti_user_dir'] . '/'. $user->id;
  list($data, $error) = vinti_fo_set($user_dir);
  if ($error === '') {
    list($data, $error) = vinti_fi_set($user_dir, 'user', json_encode($user));
  }
  return [$data, $error];
}