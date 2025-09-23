<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

require_once VINTI_DIR . '/datetime.php';
require_once VINTI_DIR . '/increment.php';

function vinti_da_ins(string $dir, string $data, string $mode = 'wb'): array
{
  list($filename, $file_handle) = vinti_increment($dir, vinti_datetime(), $mode);
  if (!is_resource($file_handle)) {
    return ['', 'cannot open file'];
  }
  if (fwrite($file_handle, $data, strlen($data)) === false) {
    return ['', 'write error'];
  }
  if (fclose($file_handle) === false) {
    return ['', 'cannot close file'];
  }
  return [$filename, ''];
}
