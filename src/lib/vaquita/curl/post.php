<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once 'result.php';

function vaquita_curl_post(string $url, string $event, $options = []): vaquita_curl_result
{
  $result = new vaquita_curl_result();

  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $event,
    CURLOPT_RETURNTRANSFER => true,
  ]);
  foreach ($options as $name => $value) {
    match ($name) {
      'ssl_verifypeer' => curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $value),
      'timeout' => curl_setopt($ch, CURLOPT_TIMEOUT, $value),
    };
  }

  $result->payload = curl_exec($ch) ?: '';

  $result->status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE) ?: 0;
  if (($errno = curl_errno($ch)) > 0) {
    $result->error = sprintf('cURL error (%d) %s' , $errno, curl_strerror($errno) ?? '');
  }
  curl_close($ch);

  return $result;
}
