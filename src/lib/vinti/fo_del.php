<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributor

declare(strict_types=1);

function vinti_fo_del(string $path): array
{
  if (!file_exists($path)) {
    return [true, ''];
  }

  $output = [];
  $exit_code = 0;
  $result = exec(sprintf("rm -rf %s", escapeshellarg($path)), $output, $exit_code);
  if ($exit_code === 0) {
    return [true, ''];
  }
  return [false, error_get_last()['message'] ?? 'unknown error'];
}
