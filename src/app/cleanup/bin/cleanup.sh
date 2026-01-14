#!/bin/sh

# Vaquita - https://dmpsee.org
# SPDX-License-Identifier: AGPL-3.0-or-later
# Copyright Digital Curation Centre (UK) and contributors

# change dir
cd /opt/vaquita/current/

# Run PHP dispatch and append stdout and stderr
/usr/bin/php app/cleanup/cli/cleanup.php >> \
  "/var/log/vaquita/cleanup.log" 2>> \
  "/var/log/vaquita/cleanup.err"
