<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function vinti_fi_set(string $dir, string $name, string $data, int $permissions = 0777): array
{
  $path = $dir . DIRECTORY_SEPARATOR . $name;
  $data = file_put_contents($path, $data);

  if (is_int($data)) {
    $old_umask = umask(0); // temporarily disable umask
    chmod($path, $permissions);
    umask($old_umask); // restore umask
    return [$data, ""];
  }
  return [0, error_get_last()['message'] ?? 'unknown error'];
}
