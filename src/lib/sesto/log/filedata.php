<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function sesto_log_filedata(?string $filename = null, ?int $flags = FILE_APPEND | LOCK_EX, bool $priority_label = false): array
{
  static $log_filename = '';
  static $log_flags = FILE_APPEND | LOCK_EX;
  static $log_priority_label = false;
  if ($filename !== null) {
    $log_filename = $filename;
    $log_flags = $flags;
    $log_priority_label = $priority_label;
  }
  return [$log_filename, $log_flags, $log_priority_label];
}