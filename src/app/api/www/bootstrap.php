<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

ini_set('display_errors', 'true');
ini_set('track_errors', 'true');
ini_set('display_startup_errors', 'true');
error_reporting(E_ALL);

define('ME', microtime(true));
define('SESTO_MEM_START', memory_get_usage(true));

/* setup the system dir */
$sys_dir = realpath(__DIR__ . '/../../..');

require $sys_dir . '/lib/sesto/initme.php';
require SESTO_DIR . '/app/run.php';

$error = '';
sesto_app_run(
  (new sesto_scd('vaquita_api_engine', [], $sys_dir . '/app/api/lib/api/engine.php')),
  ['sys_dir' => $sys_dir, 'app_name' => 'api']);

if ($error !== '') {
  echo $error;
}
