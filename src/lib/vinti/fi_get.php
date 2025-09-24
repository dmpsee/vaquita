<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function vinti_fi_get(string $dir, string $name): array
{
  $data = @file_get_contents($dir . DIRECTORY_SEPARATOR . $name);

  if (is_string($data)) {
    return [$data, ""];
  }
  return ['', error_get_last()['message'] ?? 'unknown error'];
}
