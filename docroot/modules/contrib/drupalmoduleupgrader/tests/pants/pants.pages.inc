<?php

/**
 * @file
 * Page callbacks for changing pants status.
 */

/**
 * Page callback for pants/change/%user.
 *
 * @param stdClass $account
 *   A user object.
 */
function pants_change($account) {
  $current_pants_status = isset($account->pants_status[LANGUAGE_NONE][0]['value']) ? $account->pants_status[LANGUAGE_NONE][0]['value'] : 0;
  $new_pants_status = 1 - $current_pants_status;
  $account->pants_status[LANGUAGE_NONE][0]['value'] = $new_pants_status;
  user_save($account);
  return array(
    '#theme' => 'pants_status',
    '#pants_status' => $new_pants_status,
  );
}
