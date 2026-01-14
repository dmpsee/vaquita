<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

use function sesto_log_std as slog;

require_once SESTO_DIR . '/log/std.php';

function vaquita_cleanup_engine(array $config, array $args = []): void {
  /* log */
  sesto_log_level($config['log_priority'] ?? LOG_INFO);

  slog('cleanup_start');

  $done_dir = ($config['vinti_dir'] ?? $config['vinti_queue_dir'] ?? '') . '/queue/done';

  if (!is_dir($done_dir)) {
    slog('cleanup_error queue/done directory not found', LOG_ERR);
    return;
  }

  $max_age = $config['cleanup_max_age'] ?? 10;
  $interval = $config['cleanup_interval'] ?? 5;

  while (true) {
    $files = array_slice(scandir($done_dir) ?? [], 2);

    slog(sprintf('cleanup_files_found %d', count($files)), LOG_DEBUG);
    while (!empty($files)) {
      $now = time();
      foreach ($files as $key => $file) {
        $file_path = $done_dir . '/' . $file;

        if (is_file($file_path)) {
          $file_age = $now - filemtime($file_path);

          if ($file_age >= $max_age) {
            if (@unlink($file_path)) {
              slog(sprintf('cleanup_delete %s age_%ds', $file, $file_age), LOG_INFO);
            } else {
              slog(sprintf('cleanup_failed %s', $file), LOG_INFO);
            }
            unset($files[$key]);
          }
        }

        if (empty($files)) {
          slog('cleanup_snapshot_cleared', LOG_INFO);
          break;
        }

        $now = time();
      }
      sleep(1);
    }
    sleep($interval);
  }
}
