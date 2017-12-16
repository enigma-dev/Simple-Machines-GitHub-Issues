<?php

require_once('config.php');

// Returns a dict of lower-case label names to their properties as an stdClass.
function get_all_labels() {
  global $repo;
  $res = Array();
  for ($page = 1; $page < 25; $page++) {
    $labels = JSON_decode(getPage($repo . '/labels?page=' . $page));
    if (!sizeof($labels)) break;
    foreach ($labels as $label) {
      $res[strtolower($label->name)] = $label;
    }
    if (sizeof($labels) < 15) break;
  }
  return $res;
}

var_dump(get_all_labels());

?>
