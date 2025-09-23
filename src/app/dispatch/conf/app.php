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
  'sesto_resource' => [
    'vinti' => SESTO_SYS_RES_DIR . '/vinti.php',
  ],
  'vinti_dir' => SESTO_SYS_VAR_DIR . '/storage',
  'subscriber_post' => [
    'ssl_verifypeer' => true,
    'timeout' => 10
    ]
];

$extended_file = __DIR__ . '/app.ext.php';
if (is_file($extended_file) && is_readable($extended_file)) {
  include $extended_file;
}
