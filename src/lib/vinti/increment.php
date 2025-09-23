<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function vinti_increment(string $dir, string $name, string $mode = 'wb'): array
{
  $max = 999999;
  $increment = 0;
  $created = '';
  $result = false;
  while ($increment <= 999999) {
    $filename = sprintf('%s%06d', $name, $increment);
    $path = $dir . '/' . $filename;
    if (!file_exists($path)) {
      $result = fopen($path, $mode);
      if (is_resource($result)) {
        $created = $filename;
        $increment = $max;
      }
    }
    $increment++;
  }

  return [$created, $result];
}
