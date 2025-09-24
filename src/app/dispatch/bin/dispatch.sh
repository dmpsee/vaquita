#!/bin/sh

# Vaquita - https://dmpsee.org
# SPDX-License-Identifier: AGPL-3.0-or-later
# Copyright Digital Curation Centre (UK) and contributors

# change dir
cd /opt/vaquita/current/

# Run PHP dispatch and append stdout and stderr
/usr/bin/php app/dispatch/cli/dispatch.php >> \
  "/var/log/vaquita/dispatch.log" 2>> \
  "/var/log/vaquita/dispatch.err"
