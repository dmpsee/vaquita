#!/bin/sh

# Vaquita - https://dmpsee.org
# SPDX-License-Identifier: AGPL-3.0-or-later
# Copyright Digital Curation Centre (UK) and contributors

# Vaquita user generator
# Usage: ./users.sh <url> <num_users>
# Example: ./users.sh https://test-api.dmpsee.org/post 10

URL=$1
NUM_USERS=$2

i=1
while [ $i -le "$NUM_USERS" ]; do
    USER="test-$i"
    curl -k -s -H "AC: adm-1:key-adm-1" \
         -d '["usw", ["'"$USER"'","'"$USER"'","pub"]]' \
         -X POST "$URL" >/dev/null 2>&1
    echo "Created user $USER"
    curl -k -s -H "AC: adm-1:key-adm-1" \
         -d '["eva",["plu", "'"$USER"'"]]' \
         -X POST "$URL" >/dev/null 2>&1
    echo "Allow user $USER"
    i=$((i+1))
done

echo "Done."
