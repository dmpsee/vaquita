<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

include SESTO_SYS_CONF_DIR . '/sys.php';

$config['cleanup_max_age'] = 30;
$config['cleanup_interval'] = 5;

$extended_file = __DIR__ . '/app.ext.php';
if (is_file($extended_file) && is_readable($extended_file)) {
  include $extended_file;
}
