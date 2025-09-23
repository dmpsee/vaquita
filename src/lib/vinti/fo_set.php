<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributor

declare(strict_types=1);

function vinti_fo_set(string $path, int $permissions = 0777): array
{
  if (is_dir($path)) {
    return [true, ''];
  }
  $result = mkdir($path, $permissions, true);
  if ($result) {
    $result = chmod($path, $permissions);
    if ($result) {
      return [true, ''];
    }
  }
  return [false, error_get_last()['message'] ?? 'unknown error'];
}
