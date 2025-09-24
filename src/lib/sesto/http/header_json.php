<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function sesto_http_header_json(): array
{
  return [
    'Content-Type: application/json',
    'Cache-Control: no-store',
  ];
}
