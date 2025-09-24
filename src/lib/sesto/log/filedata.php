<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function sesto_log_filedata(?string $filename = null, ?int $flags = FILE_APPEND | LOCK_EX): array
{
  static $log_filename = '';
  static $log_flags = FILE_APPEND | LOCK_EX;
  if ($filename !== null) {
    $log_filename = $filename;
    $log_flags = $flags;
  }
  return [$log_filename, $log_flags];
}