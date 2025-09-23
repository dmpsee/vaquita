<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function vinti_fi_lst(string $dir, string &$error = ''): array
{
  $files = [];
  if (!file_exists($dir)) {
    $error = 'queue does not exists';
  } else if (!is_dir($dir)) {
    $error = 'is not a dir';
  } else {
    $files = scandir($dir);
    if (is_array($files)) {
      $files = array_diff($files, ['..', '.']);
      $error = '';
    } else {
      $error = 'error on scan';
    }
  }
  return [$files, $error];
}
