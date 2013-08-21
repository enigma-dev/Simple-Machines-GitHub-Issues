<?php
/**
issues/post.php
Creates an issue. Works great.
*/

if (!isset($_POST['body'])
 || (!isset($_POST['id'])
  && !isset($_POST['title'])))
die('Missing a field. One of body, id, or title missing.');

require_once('../forums/SSI.php');
if (!$context['user']['is_logged'])
 die('Not logged in.');

if (FALSE) {
 echo '<!--  ';
 var_dump($context);
 echo '-->';
}

require_once('config.php');

$body = '(Posted by [' . $context['user']['username'] . '](' . $forum_url . '?action=profile;u=' . $context['user']['id'] . ') on ' . $forum_name . ')' . "\n\n" . $_POST['body'];

//echo 'ERROR: Ticket creation and commenting is currently under construction. Please either use github or try again tomorrow. Sorry for the inconvenience.<hr />';

if (isset($_POST['id'])) {
 $url = $repo . '/issues/' . $_POST['id'] . '/comments';

 $json['body'] = $body;
 $data = json_encode($json);
} else {
 $url = $repo . '/issues';

 $title = $_POST['title'] . ' [u' . $context['user']['id'] . ']';

 $labels = explode(',',$_POST['labels']);
 for ($i = 0; $i < count($labels); $i++)
  $labels[$i] = trim($labels[$i]);
 $labels = array_filter($labels);

 $json['title'] = $title;
 $json['body'] = $body;
 $json['labels'] = $labels;

 $data = json_encode($json);
}
//echo $url . '<hr />';
//echo '<textarea rows="10" cols="100">' . $data . '</textarea>';

//Prevent further execution (which actually posts)
//die();

$result = getPage($url,$data);

//echo $result . "<br />\n<br />\n";
$js = json_decode($result);

if (count($js) == 1 && isset($js->message)) {
  include('common.php');
  die('<div id="down">Post failed because the GitHub API is a cryptic assault on the mind. Reason for failure:<br/>' . $js->message . '</div>'
  . '<br/>Dump of post text, in case you miss it:<br/><textarea cols="96" rows="7">' . htmlspecialchars($_POST['body']) . '</textarea>'
  . '<br/><br/>Additional info: Post to url "' . $url . '" failed.<br/><br/>Sent:<br/>' . $data . '<br/><br/>Received:<br/>' . $result);
}

if (isset($_POST['id']))
  $num = $_POST['id'];
else
  $num = $js->number;

header('Location: ' . $tracker_url . '?id=' . $num);
?>
