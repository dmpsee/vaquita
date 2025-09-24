<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

include SESTO_SYS_CONF_DIR . '/sys.php';

$config['sesto_require'][] = SESTO_APP_LIB_DIR . '/api/initme.php';
$config['log_filename'] = '/var/log/vaquita/api.log';

$extended_file = __DIR__ . '/app.ext.php';
if (is_file($extended_file) && is_readable($extended_file)) {
  include $extended_file;
}
