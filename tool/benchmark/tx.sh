#!/bin/sh

# Vaquita - https://dmpsee.org
# SPDX-License-Identifier: AGPL-3.0-or-later
# Copyright Digital Curation Centre (UK) and contributors

# Minimal bulk sender
# Usage: ./tx.sh <url> <num_users> <tests_per_user> <concurrency> <test_prefix>
# Example: ./tx.sh https://test-api.dmpsee.org/post 100 10 5 T001

URL=$1
NUM_USERS=$2
TESTS_PER_USER=$3
CONCURRENCY=$4
TEST_PREFIX=$5
SLEEP_BETWEEN=0.05

# read static details JSON once
DETAILS=$(tr -d '\n' < details.json)

# build user list: test-1, test-2, ...
users_tmp=$(mktemp)
i=1
while [ $i -le "$NUM_USERS" ]; do
  echo "test-$i" >> "$users_tmp"
  i=$((i+1))
done

# shuffle
shuf_tmp=$(mktemp)
awk 'BEGIN{srand()} {printf("%f %s\n", rand(), $0)}' "$users_tmp" | sort -n | cut -d' ' -f2- > "$shuf_tmp"
rm -f "$users_tmp"

# split into groups
GROUP_SIZE=$((NUM_USERS / CONCURRENCY))
group_files=""
cnt=0
gf=""
while IFS= read -r u; do
  if [ $((cnt % GROUP_SIZE)) -eq 0 ]; then
    gf=$(mktemp)
    group_files="$group_files $gf"
  fi
  echo "$u" >> "$gf"
  cnt=$((cnt+1))
done < "$shuf_tmp"
rm -f "$shuf_tmp"

# worker
worker() {
  gf="$1"
  while IFS= read -r user; do
    n=1
    while [ $n -le "$TESTS_PER_USER" ]; do
      seqid=$(printf "%03d" "$n")
      payload='["evp", ["plu", "'"$TEST_PREFIX-$user-$seqid"'", '"$DETAILS"']]'
      curl -k -s -H "AC: $user:$user" -d "$payload" -X POST "$URL" >/dev/null 2>&1
      printf '%s sent %s\n' $user $TEST_PREFIX-$user-$seqid
      sleep "$SLEEP_BETWEEN"
      n=$((n+1))
    done
  done < "$gf"
  rm -f "$gf"
}

# launch workers
for f in $group_files; do
  worker "$f" &
done

wait
echo done
