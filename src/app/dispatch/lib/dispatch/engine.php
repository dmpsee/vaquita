<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

use function sesto_log_std as slog;

require_once SESTO_DIR . '/log/std.php';
require_once SESTO_DIR . '/http/status_codes.php';
require_once VAQUITA_DIR . '/queue/new.php';
require_once VAQUITA_DIR . '/queue/active.php';
require_once VAQUITA_DIR . '/queue/read.php';
require_once VAQUITA_DIR . '/queue/move.php';
require_once VAQUITA_DIR . '/event/subscribers.php';
require_once VAQUITA_DIR . '/url/read.php';
require_once VAQUITA_DIR . '/curl/post.php';

function dmpseee_dispatch_engine(array $config, array $args = []): void
{
  /* log */
  sesto_log_level($config['log_priority'] ?? LOG_INFO);

  slog('dispatch_start');
  $config['vinti_user_dir'] = $config['vinti_dir'] . '/user';
  $config['vinti_token_dir'] = $config['vinti_dir'] . '/key';
  $config['vinti_sub_dir'] = $config['vinti_dir'] . '/sub';
  $config['vinti_url_dir'] = $config['vinti_dir'] . '/url';
  $config['vinti_event_dir'] = $config['vinti_dir'] . '/event';
  $config['vinti_queue_dir'] = $config['vinti_dir'] . '/queue';
  list($events, $error) = vaquite_queue_new($config);

  if ($error !== '') {
    echo $error;
    return;
  }

  $num_files = count($events);
  slog(sprintf('events_founds %d', $num_files));

  $to_process = [];

  /* event_active */
  foreach($events as $event_id) {
    list($done, $error) = vaquite_queue_move($config, $event_id, 'new', 'active');
    if ($error === '') {
      $log_priority = LOG_INFO;
      $to_process[] = $event_id;
    } else {
      $log_priority = LOG_ERR;
    }
    slog(sprintf('event_active %s', $event_id), $log_priority);
  }

  /* process events */
  $event_subscribers = [];
  $subscriber_urls = [];
  foreach($to_process as $event_id) {
    /* event_read */
    list($event, $error) = vaquita_queue_read($config, $event_id, 'active');
    if ($error !== '') {
      slog(sprintf('event_read %s %s', $event_id, $error), LOG_ERR);
    } else {
      slog(sprintf('event_read %s', $event_id), LOG_INFO);
      /* event_decode */
      $event = json_decode($event, true);
      if ($event === null) {
        slog(sprintf("event_decode %s %s\n", $event_id, json_last_error_msg()), LOG_ERR);
      } else {
        slog(sprintf('event_decode %s %s', $event_id, $event[0]), LOG_INFO);
        /* event_subscribers */
        if (!isset($event_subscribers[$event[0]])) {
          slog(sprintf('event_subscribers %s %s', $event[0], 'not_exists'), LOG_INFO);
          list($subscribers, $error) = vaquita_event_subscribers($config, $event[0]);
          if ($error !== '') {
            slog(sprintf('subscribers_retrieve %s %s %s', $event_id, $event[0], $error), LOG_ERR);
          } else {
            slog(sprintf('subscribers_retrieve %s %s %d', $event_id, $event[0], count($subscribers)), LOG_INFO);
            $event_subscribers[$event[0]] = array_values($subscribers);
          }
        } else {
          slog(sprintf('event_subscribers %s %s %s', $event_id, $event[0], 'exists'), LOG_INFO);
        }
        foreach($event_subscribers[$event[0]] as $subscriber_id) {
          /* url_read */
          if (!isset($subscriber_urls[$subscriber_id])) {
            slog(sprintf('subscriber_url %s %s', $subscriber_id, 'not_exists'), LOG_INFO);
            list($url, $error) = vaquita_url_read($config, $subscriber_id);
            if ($error !== '') {
              slog(sprintf("url_read %s: %s\n", $event_id, $error), LOG_ERR);
            } else {
              slog(sprintf('url_read %s %s', $subscriber_id, $url), LOG_INFO);
              $subscriber_urls[$subscriber_id] = $url;
            }
          } else {
            slog(sprintf('subscriber_url %s %s', $subscriber_id, 'exists'), LOG_INFO);
          }
          /* subscriber post */
          $post_result = vaquita_curl_post(
            $subscriber_urls[$subscriber_id],
            $event[1],
            $config['subscriber_post'] ?? []);
          slog(sprintf('post %s %s %s %s', $event_id, $subscriber_id, $post_result->status_code, $subscriber_urls[$subscriber_id]));

        }
      }
    }
    /* event_done */
    list($done, $error) = vaquite_queue_move($config, $event_id, 'active', 'done');
    if ($error !== '') {
      slog(sprintf('event_done %s %s', $event_id, $error), LOG_ERR);
    } else {
      slog(sprintf('event_done %s', $event_id));
    }

  }

}
