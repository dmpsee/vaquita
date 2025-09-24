<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function sesto_log_level(?int $level = null): int
{
  static $log_level = LOG_INFO;
  if ($level === null) {
    return $log_level;
  }
  $old_level = $log_level;
  $log_level = $level;
  return $old_level;
}