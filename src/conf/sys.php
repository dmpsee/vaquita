<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

$config = [
  'sesto_php_ini_set' => [
    'track_errors' => 'true',
    'display_startup_errors' => 'true'
  ],
  'sesto_require' => [
    SESTO_SYS_LIB_DIR . '/vinti/initme.php',
    SESTO_SYS_LIB_DIR . '/vaquita/initme.php',
  ],
  'sesto_error_strict' => true,
  'log_flags' => FILE_APPEND | LOCK_EX,
  'log_priority' => LOG_INFO,
  'vinti_dir' => SESTO_SYS_VAR_DIR . '/storage'
];

$extended_file = __DIR__ . '/sys.ext.php';
if (is_file($extended_file) && is_readable($extended_file)) {
  include $extended_file;
}
