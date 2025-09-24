<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

require SESTO_DIR . '/log/level.php';
require SESTO_DIR . '/log/label.php';

function sesto_log_std(string $message, int $priority = LOG_INFO, array $context = []): bool
{
  if ($priority > sesto_log_level()) {
    return true;
  }

  $log_string = json_encode(array_merge([gmdate('c'), $priority, $message], $context), JSON_UNESCAPED_SLASHES);

  if ($log_string === false) {
    file_put_contents('php://stderr', "ERROR: Failed to encode log message\n", FILE_APPEND);
    return false;
  }

  if (file_put_contents('php://stdout', $log_string . PHP_EOL, FILE_APPEND) === false) {
    file_put_contents('php://stderr', "ERROR: Failed to write log to stdout.\n", FILE_APPEND);
    return false;
  }

  return true;
}
