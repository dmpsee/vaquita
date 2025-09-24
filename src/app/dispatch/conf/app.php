<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

include SESTO_SYS_CONF_DIR . '/sys.php';

$config['subscriber_post'] = [
  'ssl_verifypeer' => true,
  'timeout' => 10
];

$extended_file = __DIR__ . '/app.ext.php';
if (is_file($extended_file) && is_readable($extended_file)) {
  include $extended_file;
}
