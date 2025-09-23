<?php

// Vaquita - https://dmpsee.org
// SPDX-License-Identifier: AGPL-3.0-or-later
// Copyright Digital Curation Centre (UK) and contributors

declare(strict_types=1);

require_once SESTO_DIR . '/log/syslog.php';
require_once SESTO_DIR . '/http/status_codes.php';
require_once VAQUITA_DIR . '/queue/new.php';
require_once VAQUITA_DIR . '/queue/active.php';
require_once VAQUITA_DIR . '/queue/read.php';
require_once VAQUITA_DIR . '/event/subscribers.php';
require_once VAQUITA_DIR . '/url/read.php';
require_once VAQUITA_DIR . '/curl/post.php';

function dmpseee_dispatch_engine(array $config, array $args = []): void
{

  /* log */
  // openlog("estro", $config['log_flags'] ?? LOG_PID, LOG_LOCAL0);
  // sesto_syslog('__set', $config['log_priority'] ?? LOG_INFO);

  echo "hello I am " . __FUNCTION__  . "\n";
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
  echo sprintf("Events %d\n", $num_files);

  sesto_d($events);

  $to_process = [];

  /* set active */
  foreach($events as $event_id) {
  //  list($is_active, $error) = vaquite_queue_active($config, $event_id);
    $error = '';
    if ($error === '') {
      echo sprintf('set active %s', $event_id);
      $to_process[] = $event_id;
    } else {
      echo sprintf('Error %s Unable to move', $event_id);
    }
  }

  /* process events */
  $event_subscribers = [];
  $subscriber_urls = [];
  foreach($to_process as $event_id) {
    echo sprintf("Read %s\n", $event_id);
    list($event, $error) = vaquita_queue_read($config, $event_id, 'new');
    if ($error !== '') {
    } else {
      $event = json_decode($event, true);
      sesto_d($event, 'event');
      if ($event === null) {
      } else {
        if (!isset($event_subscribers[$event[0]])) {
          list($subscribers, $error) = vaquita_event_subscribers($config, $event[0]);
          if ($error !== '') {

          } else {
            $event_subscribers[$event[0]] = array_values(vaquita_event_subscribers($config, $event[0])[0]);
          }
        }
        foreach($event_subscribers[$event[0]] as $subscriber_id) {
          if (!isset($subscriber_urls[$subscriber_id])) {
            list($url, $error) = vaquita_url_read($config, $subscriber_id);
            if ($error !== '') {

            } else {
              $subscriber_urls[$subscriber_id] = $url;
            }
          }
          $post_result = vaquita_curl_post(
            $subscriber_urls[$subscriber_id],
            $event[1],
            $config['subscriber_post'] ?? []);

          echo sprintf("Post %s %d %s \n", $event_id, $post_result->status_code, $subscriber_id);
        }
      }
      sesto_d($event_subscribers, '$event_subscribers');
      sesto_d($subscriber_urls, '$subscriber_urls');
    }
  }

}
