<?php

// Naranza Vinti - https://naranza.org
// SPDX-License-Identifier: AGPL-3.0
// Copyright (c) Andrea Davanzo and contributor

declare(strict_types=1);

function vinti_datetime(): string
{
  return date('YmdHis') . sprintf('%06d', (int) (microtime(true) * 1000) % 1000000);
}
