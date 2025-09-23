<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/fo_set.php';

function vaquita_event_write(array $config, string $event): array
{
  $event_dir = $config['vinti_event_dir'] . '/'. $event;
  list($data, $error) = vinti_fo_set($event_dir);
  if ($error === '') {
    list($data, $error) = vinti_fo_set($event_dir . '/sub');
    if ($error === '') {
      list($data, $error) = vinti_fo_set($event_dir . '/pub');
    }
  }
  return [$data, $error];
}