<?php

// Naranza Sesto - https://naranza.org
// SPDX-License-Identifier: MPL-2.0
// Copyright (c) Andrea Davanzo and contributors

declare(strict_types=1);

const SESTO_HTTP_OK = 200;
const SESTO_HTTP_CREATED = 201;
const SESTO_HTTP_BAD_REQUEST = 400;
const SESTO_HTTP_UNAUTHORIZED = 401;
const SESTO_HTTP_FORBIDDEN = 403;
const SESTO_HTTP_NOT_FOUND = 404;
const SESTO_HTTP_METHOD_NOT_ALLOWED = 405;
const SESTO_HTTP_GONE = 410;
const SESTO_HTTP_UNPROCESSABLE_CONTENT = 422;
const SESTO_HTTP_INTERNAL_SERVER_ERROR = 500;

function sesto_http_status_codes(): array
{
  return [
    SESTO_HTTP_OK => 'OK',
    SESTO_HTTP_CREATED => 'Created',
    SESTO_HTTP_BAD_REQUEST => 'Bad Request',
    SESTO_HTTP_UNAUTHORIZED => 'Unauthorized',
    SESTO_HTTP_FORBIDDEN => 'Forbidden',
    SESTO_HTTP_NOT_FOUND => 'Not Found',
    SESTO_HTTP_METHOD_NOT_ALLOWED => 'Method Not Allowed',
    SESTO_HTTP_GONE => 'Gone',
    SESTO_HTTP_UNPROCESSABLE_CONTENT => 'Unprocessable Content',
    SESTO_HTTP_INTERNAL_SERVER_ERROR => 'Internal Server Error',
  ];
}
