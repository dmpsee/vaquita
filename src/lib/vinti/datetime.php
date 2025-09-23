<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributor

declare(strict_types=1);

function vinti_datetime(): string
{
  $now = microtime(true);
  return date('YmdHis', (int) $now) . sprintf('%06d', ($now - floor($now)) * 1e6);
}
