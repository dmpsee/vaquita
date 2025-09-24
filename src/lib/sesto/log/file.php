<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

require SESTO_DIR . '/log/filedata.php';
require SESTO_DIR . '/log/level.php';
require SESTO_DIR . '/log/label.php';

function sesto_log_file(string $message, int $priority = LOG_INFO, array $context = []): bool
{
  if ($priority > sesto_log_level()) {
    return true;
  }

  $log_array = [gmdate('c'), sesto_log_label($priority), $message];
  if (!empty($context)) {
    $log_array[] = $context;
  }

  $log_string = json_encode($log_array, JSON_UNESCAPED_SLASHES);

  if ($log_string === false) {
    file_put_contents('php://stderr', "ERROR: Failed to encode log message\n", FILE_APPEND);
    return false;
  }
  list($logfile, $flags) = sesto_log_filedata();
  if (file_put_contents($logfile, $log_string . PHP_EOL, $flags) === false) {
    file_put_contents('php://stderr', "ERROR: Failed to write log to stdout.\n", FILE_APPEND);
    return false;
  }

  return true;
}
