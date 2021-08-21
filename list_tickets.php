<?php

function display_ticket_list($closed_only) {
  global $repo, $orgrepo;
  global $gh_username;
  global $forum_url, $memberContext;
  $page = $repo . '/issues';

  echo '
    <h1 class="imgtitle imgtitle-32">
	    <img alt="" src="tickets.png"> Tickets
            <div style="float:right"><a href="?new"><img src="tickets-add.png"> Post New</a></div>
    </h1>
  ';

  if ($closed_only) {
    $page .= '?state=closed';
    echo '<a href="index.php">View open issues</a>';
  } else
    echo '<a href="index.php?closed">View closed issues</a>'
       . ' &nbsp;|&nbsp; '
       . '<a href="https://www.github.com/' . $orgrepo . '/issues">View on GitHub</a>'
       . ' &nbsp;|&nbsp; '
       . '<a href="?new">Submit new issue</a>';

  $js = json_decode(getPage($page));
  $fields = array('ID' => '32px','Title' => 'auto','Reporter' => 'auto','Asignee' => 'auto','Comments' => '72px','Date' => '220px');

  if ($js === NULL) die('<div id="down">Tracker is down for Github routine maintenence. Please try again later.</div>');

  if (count($js) == 1 && isset($js->message)) {
    die('<div id="down">Tracker is down because the GitHub API is a cryptic assault on the mind. Reason for failure:<br/>'
        . $js->message . '</div>');
  }

  foreach ($js as $issue) {
    if ($issue->user->login == $gh_username) {
      $num = array();
      preg_match('/\[u([0-9]+)\]$/',$issue->title,$num);
      if (array_key_exists(1,$num)) {
        $issue->from_forums = true;
        $nid = $num[1] + 0;
        if (($lmd = loadMemberData(array($nid))) !== false) {
          $lmc = loadMemberContext($lmd[0]);
          $author_info = $memberContext[$lmd[0]];
          $issue->user->login = $author_info['name'];
          $avaurl = $author_info['avatar']['href'];
          if (!empty($avaurl))
            $issue->user->avatar_url = $avaurl;
          $issue->user->url = $forum_url . '?action=profile;u=' . $author_info['id'];
        }
      } else {
        $issue->from_forums = false;
        $issue->user->url = 'https://github.com/' . $issue->user->login;
      }
    } else {
      $issue->from_forums = false;
      $issue->user->url = 'https://github.com/' . $issue->user->login;
    }
  }
    
  echo '<table id="issuetable">' . "\n  <tr>";
  foreach ($fields as $field => $fieldwidth) echo '<th style="width: ' . $fieldwidth . ';">' . $field . '</th>';
  echo "</tr>\n";

  $rn = 0; // Track our row number
  foreach ($js as $issue) {
    echo '<tr' . ($rn++ % 2? ' style="background: #EFF8FF"' : '') . '><td>' . $issue->number . '</td><td>';
    echo '<a href="index.php?id=' . $issue->number . '">';
    echo $issue->title . '</a></td><td>';

    // Reporter stuff
    echo '<img src="' . $issue->user->avatar_url . '" width="16" height="16" style="vertical-align: middle;"> ';
    echo '<a href="' . $issue->user->url . '" style="vertical-align: middle;'  . ($issue->from_forums? '' : ' font-style:italic;') . '"';
    echo '>' . $issue->user->login . '</a>';
    echo '</td><td>';

    // Asignee stuff
    if (isset($issue->assignee)) {
      echo '<img src="' . $issue->assignee->avatar_url . '" width="16" height="16" style="vertical-align: middle;"> ';
      echo '<a href="https://github.org/' . $issue->assignee->login . '" style="vertical-align: middle; font-style:italic;">' . $issue->assignee->login . '</a>';
    }
    echo '</td><td>';

    echo $issue->comments . '</td><td>';
    echo timeformat(strtotime($issue->created_at)) . '</td></tr>';
  }

  echo "</table>";
}

?>

