<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

require_once SESTO_DIR . '/app/define.php';
require_once SESTO_DIR . '/app/resource.php';
require_once SESTO_DIR . '/config/php.php';
require_once SESTO_DIR . '/util/ini_set_array.php';
require_once SESTO_DIR . '/util/require_array.php';
require_once SESTO_DIR . '/util/exit.php';
require_once SESTO_DIR . '/util/registry.php';
require_once SESTO_DIR . '/error/handler.php';
require_once SESTO_DIR . '/util/registry.php';
require_once SESTO_DIR . '/scd/scd.php';
require_once SESTO_DIR . '/scd/call.php';

function sesto_app_run(
  sesto_scd $scd,
  array $args = [],
): array
{
  $exit_code = 0;
  $error = '';
  $error_handler = null;

  /* normalise args */
  $args['sys_dir'] = (string) ($args['sys_dir'] ?? true);
  $args['app_name'] = (string) ($args['app_name'] ?? true);
  try {
    /* define constants */
    $define_error = sesto_app_define($args['sys_dir'], $args['app_name']);
    if ($define_error !== '') {
      throw new exception($define_error);
    }

    /* load and parse app.php config */
    $config = sesto_config_php(SESTO_APP_CONF_DIR . '/app.php');
    if (is_array($config)) {
      /* parse ini_set */
      sesto_ini_set_array($config['sesto_php_ini_set'] ?? []);

      /* parse require */
      sesto_require_array($config['sesto_require'] ?? []);

      /* parse env */
      foreach ($config['sesto_registry'] ?? [] as $name => $value) {
        sesto_registry($name, $value);
      }

      /* parse resource */
      foreach ($config['sesto_resource'] ?? [] as $name => $value) {
        sesto_resource($name, $value);
      }

      /* error_strict */
      if ($config['sesto_error_strict'] ?? true) {
        set_error_handler("sesto_error_handler");
      }
      if (!isset($config['sesto_app_error_handler'])) {
        $error_handler = null;
      } else {
        $error_handler = $config['sesto_app_error_handler'];
      }

      if (null !== $error_handler && !is_callable($error_handler)) {
        throw new exception('Error handler not callale');
      }
    }

    if (null === $error_handler) {
      if ('cli' == php_sapi_name()) {
        require_once SESTO_DIR . '/app/error_cli.php';
        $error_handler = 'sesto_app_error_cli';
      } else {
        require_once SESTO_DIR . '/app/error_web.php';
        $error_handler = 'sesto_app_error_web';
      }
    }
    sesto_scd_call($scd, $config, $args);
  } catch (sesto_exit $throwable) {
    /* do nothing */
  } catch (throwable $throwable) {
    /* check if output buffer is started */
    if (ob_get_length() > 0) {
      ob_clean();
      ob_end_clean();
    }
    if (null !== $error_handler) {
      call_user_func_array($error_handler, [$throwable, $config, $args]);
    }
    $exit_code = 1;
    $error = $throwable->getmessage();
  }
  return [$exit_code, $error];
}
