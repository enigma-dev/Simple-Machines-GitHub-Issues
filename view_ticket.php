<?php

function display_single_ticket($issue) {
  global $repo, $orgrepo;
  global $gh_username, $gh_password;
  global $forum_name, $forum_url, $memberContext;
  $page = $repo . '/issues';

  $js = json_decode(getPage($page . '/' . $issue));

  // This is the regexp we use to check if the comment is from our forum.
  $pregstr = '/^\(Posted by !?\[.*?\]\(' . preg_quote($forum_url, '/') . '\?action=profile;u=([0-9]+)\) on ' . $forum_name . '\)/';

  $from_forums = false;
  $js->user->url = 'http://github.org/' . $js->user->login;
  if ($js->user->login == $gh_username) {
    $num = array();
    preg_match('/\[u([0-9]+)\]$/',$js->title,$num);
    if (array_key_exists(1,$num)) {
      $from_forums = true;
      $nid = $num[1] + 0;
      if (($lmd = loadMemberData(array($nid))) !== false) {
        $lmc = loadMemberContext($lmd[0]);
        $author_info = $memberContext[$lmd[0]];
        $js->user->login = $author_info['name'];
        $avaurl = $author_info['avatar']['href'];
        if (!empty($avaurl))
          $js->user->avatar_url = $avaurl;
        $js->user->url = $forum_url . '?action=profile;u=' . $author_info['id'];
        
        // Remove (Posted by ...) message
        $match = array();
        preg_match($pregstr, $js->body, $match);
        if (array_key_exists(1,$match))
          $js->body = substr($js->body, strlen($match[0]));
      }
    }
  }
  
  // Fetch comments
  $comments = json_decode(getPage($page . '/' . $issue . '/comments'));
  $bodies = array();
  foreach ($comments as $ind => $comment) {
    $comment->forum_user = false;
    if ($comment->user->login == $gh_username) {
      $match = array();
      preg_match($pregstr,$comment->body,$match);
      if (array_key_exists(1,$match)) {
        $bodies[$ind] = substr($comment->body, strlen($match[0]));
        $nid = $match[1] + 0;
        if (($lmd = loadMemberData(array($nid))) !== false) {
          $lmc = loadMemberContext($lmd[0]);
          $comment->forum_user = true;
          $author_info = $memberContext[$lmd[0]];
          $comment->user->login = $author_info['name'];
          $avaurl = $author_info['avatar']['href'];
          if (!empty($avaurl))
            $comment->user->avatar_url = $avaurl;
          $comment->user->url = $forum_url . '?action=profile;u=' . $author_info['id'];
        }
      }
      else { // No match? Push the regular comment body.
        // This should normally not happen, unless the bot that made the post is also a regular poster.
        $bodies[$ind] = 'UNMATCHED: ' . $comment->body;
        echo '<div id="down">Match failure.<br/><textarea cols="128" rows="1">' . $pregstr . '</textarea><br/><textarea cols="128" rows="1">' . $comment->body . '</textarea></div>';
      }
    }
    else { // Regular comment body.
      $bodies[$ind] = $comment->body;
      $comment->user->url = 'http://github.org/' . $comment->user->login;
    }
  }
  
  // Parse the markdown of the $bodies array and $js->body
  $bodies2 = $bodies;
  $bodies2[] = $js->body;
  $bodies2 = markdown($bodies2);

  // Print the issue
  echo '<div id="issueheader">';
  echo '<div id="issueavatar">';
  echo '<a style="vertical-align:middle;" href="' . $js->user->url . '"><img src="'
     . $js->user->avatar_url . '" style="vertical-align:middle;" alt="" width="64" height="64"></a>';
  echo '</div><div id="issueinfo">';
    echo '<h1 style="padding: 2px 0;"><a href="?">' . $js->title . '</a></h1>';
    echo 'Reporter: <a href="' . $js->user->url . '"';
      if (!$from_forums) echo ' style="font-style:italic"';
        echo '>' . $js->user->login . '</a> &nbsp;|&nbsp; ';
    echo 'Status: ' . $js->state . ' &nbsp;|&nbsp; ';
    echo 'Last Modified: ' . timeformat(strtotime($js->updated_at));
    echo '<hr/></div>';
  echo '</div>';
  echo '<div style="padding: 4px 32px">' . $bodies2[count($bodies2) - 1] . '</div>';
  
  // Output each comment
  foreach ($comments as $ind => $comment) {
    echo '<div class="commenthead">';
    echo '<img src="' . $comment->user->avatar_url
       . '" alt="" style="vertical-align:middle;" width="16" height="16" /> ';
    echo '<a style="vertical-align:middle;' . ($comment->forum_user? '' : ' font-style:italic;')
        . '" href="' . $comment->user->url . '">' .  $comment->user->login . '</a> &nbsp; ';
    echo '<time class="timefield" datetime="' . $comment->created_at . '" pubdate="" title="' . $comment->created_at . '" >'
        . timeformat(strtotime($comment->created_at)) . '</time>'; // This was supposed to be a nicely formated F j, Y; ie, December 21, 2012.
        //. (new DateTime($dateTimeString))->format('F j, Y') . '</time>';
    echo '</div>';
    echo '<div style="padding: 0 32px">' . $bodies2[$ind] . '</div>';
  }
  
  // Place to leave a comment
  echo '<div class="commenthead">Leave a comment';
  echo '<a style="float:right;" href="https://github.com/' . $orgrepo . '/issues/' . $issue . '">View this issue on GitHub</a>';
  echo '</div>';

  if ($context['user']['is_logged']) {
    $avi = isset($context['user']['avatar']['image']) ? $context['user']['avatar']['image'] : "";
    echo '<form method="post" action="post.php">';
    echo '<table id="postformtable"><tbody><tr><td rowspan="2" id="useravatar">' 
       . ($avi) . '</td>';
    // Formatting buttons
    echo '<td>';
      include("editorbuttons.php");
    echo '<input type="hidden" name="id" value="' . $issue . '" />'
       . '<input type="submit" value="Post comment" style="float:right"></td></tr>';
    echo '<tr><td><textarea name="body" id="commentfield" rows="4" style="width: 100%" placeholder="Leave a comment via ' . $forum_name . '">'
       . '</textarea></td></tr></tbody></table>';
    echo '</form>';
  }
  else {
    echo 'Please sign in to post comments, or you can <a href="https://github.com/' . $orgrepo . '/issues/' . $issue . '">view this issue on GitHub.';
  }
}

?>

