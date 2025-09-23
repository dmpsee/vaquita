<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function vinti_fi_ren(string $folder, string $file, string $to): array
{
    $source_path = $folder . DIRECTORY_SEPARATOR . $file;

    if (rename($source_path, $to)) {
      return [true, ""];
    }

    return [false, error_get_last()['message'] ?? 'unknown error'];
}
