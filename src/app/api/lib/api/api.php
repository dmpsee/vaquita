<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once SESTO_DIR . '/url/url.php';
require_once VAQUITA_API_DIR . '/request.php';
require_once VAQUITA_API_DIR . '/response.php';

final class vaquita_api
{
  public array $config = [];
  public array $args = [];
  public ?sesto_url $url;
  public string $controller_dir = '';
  public string $client_type = '';
  public ?vaquita_api_request $request;
  public ?vaquita_api_response $response;
  public ?vaquita_user $user;

}
