<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function vinti_fi_del(string $dir, string $name): array
{
  $path = $dir . DIRECTORY_SEPARATOR . $name;

  if (!file_exists($path)) {
    return [true, ''];
  }

  return (unlink($path)) ? [true, ""] : [false, error_get_last()['message'] ?? 'unknown error'];
}
