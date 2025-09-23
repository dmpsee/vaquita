<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function vinti_fi_has(string $dir, string $name): array
{
  return file_exists($dir . DIRECTORY_SEPARATOR . $name) ? [true, ""] : [false, 'file does not exist'];
}
