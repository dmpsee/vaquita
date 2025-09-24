<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

require_once SESTO_DIR . '/log/level.php';

function sesto_syslog(string $message, int $priority = LOG_INFO): bool
{
  if ($priority > sesto_log_level()) {
    return true;
  }
  return syslog($priority, $message);
}
