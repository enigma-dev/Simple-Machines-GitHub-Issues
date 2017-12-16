<?php
function display_new_ticket_form() {
  if ($context['user']['is_logged']) {
    echo '<form method="post" action="post.php">';
    echo '<div id="issueheader"><div id="issueavatar">';
    echo $context['user']['avatar']['image'];
    echo '</div><div id="issueinfo">';
    echo '<h1 style="padding: 2px 0;"><div style="float:left">Title:</div> ';
    echo '<div style="margin-left:64px; margin-right:100px;">'
       . '<input type="text" name="title" style="width:100%" placeholder="A succinct title for your issue or suggestion"/>'
       . '</div></h1>';
    echo '<div style="float:left;">Labels:</div><div style="margin-left:48px; margin-right:100px;">'
       . '<input type="text" name="labels" style="width:100%" placeholder="Comma separated; eg, Parser, Event System, Compile Error"/></div>';
    echo '<hr/></div></div>';
    
    echo '<div style="display:block; clear:both; padding-top: 12px;">';
      include('editorbuttons.php');
      echo '<input type="submit" value="Post issue" style="float:right">';
    echo '<br/>';
    echo '<textarea name="body" id="commentfield" style="width: 100%" rows="15" '
       . 'placeholder="Enter a description of your issue or suggestion here. Be thorough first, and brief second."'
       . '></textarea>';
    echo '</div>';
  }
  else {
    echo 'Please sign in to post comments; or you can <a href="https://github.com/' . $orgrepo . '/issues/">view these issues on GitHub</a>.';
  }
}

?>

