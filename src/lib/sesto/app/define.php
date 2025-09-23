<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

function sesto_app_define(string $sys_dir, string $app_name = ''): string
{
  if (!is_dir($sys_dir) || !is_readable($sys_dir)) {
    $error = sprintf('%s is not a directory or not readable', $sys_dir);
    define('SESTO_SYS_INIT', false);
  } else {
//    define('SESTO_SYS_STARTED_AT', microtime(true));
    define('SESTO_SYS_DIR', $sys_dir);
    define('SESTO_SYS_APP_DIR', $sys_dir . '/app');
    define('SESTO_SYS_BIN_DIR', $sys_dir . '/bin');
    define('SESTO_SYS_CLI_DIR', $sys_dir . '/cli');
    define('SESTO_SYS_CONF_DIR', $sys_dir . '/conf');
    define('SESTO_SYS_LIB_DIR', $sys_dir . '/lib');
    define('SESTO_SYS_LOG_DIR', $sys_dir . '/log');
    define('SESTO_SYS_RES_DIR', $sys_dir . '/res');
    define('SESTO_SYS_SHARE_DIR', $sys_dir . '/share');
    define('SESTO_SYS_VAR_DIR', $sys_dir . '/var');
    define('SESTO_SYS_VIEW_DIR', $sys_dir . '/view');
    define('SESTO_SYS_USR_DIR', $sys_dir . '/usr');
    define('SESTO_SYS_WWW_DIR', $sys_dir . '/www');
    define('SESTO_SYS_INIT', true);

    /* define app constants */
    define('SESTO_APP_NAME', strtolower($app_name));

    if ($app_name !== '') {
      $app_dir = SESTO_SYS_APP_DIR . '/' . $app_name;
    } else {
      $app_dir = $sys_dir;
    }
    if (!is_dir($app_dir) || !is_readable($app_dir)) {
      define('SESTO_APP_INIT', false);
      $error = sprintf('%s is not a directory or not readable', $app_dir);
    } else {
      define('SESTO_APP_DIR', $app_dir);
      define('SESTO_APP_BIN_DIR', $app_dir . '/bin');
      define('SESTO_APP_CLI_DIR', $app_dir . '/cli');
      define('SESTO_APP_CONF_DIR', $app_dir . '/conf');
      define('SESTO_APP_LIB_DIR', $app_dir . '/lib');
      define('SESTO_APP_LOG_DIR', $app_dir . '/log');
      define('SESTO_APP_RES_DIR', $app_dir . '/res');
      define('SESTO_APP_SRC_DIR', $app_dir . '/src');
      define('SESTO_APP_USR_DIR', $app_dir . '/usr');
      define('SESTO_APP_VAR_DIR', $app_dir . '/var');
      define('SESTO_APP_VIEW_DIR', $app_dir . '/view');
      define('SESTO_APP_WWW_DIR', $app_dir . '/www');
      define('SESTO_APP_INIT', true);
      $error = '';
    }
  }
  return $error;
}
