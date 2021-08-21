<?php

include('config.php');

include('gsoc/header.html');

$json = json_decode(getPage('https://api.github.com/repos/enigma-dev/enigma-dev/issues?labels=GSOC'), true);

#echo 'Last error: ', json_last_error_msg(), PHP_EOL, PHP_EOL;

$marked_down = array();
foreach ($json as $key => $value) {
  $marked_down[$key] = $value["body"];
}
$marked_down = markdown($marked_down);

foreach ($json as $key => $value) {
  echo '<div class="Idea">' . "\n";
    
    echo "\t" . '<div class="IdeaTitle">'  . "\n";
    echo "\t" . $value["title"]  . "\n";
    echo "\t" . '</div>'  . "\n";
    
    echo "\t" . '<div class="IdeaDesc">'  . "\n";
    echo "\t" . $marked_down[$key]  . "\n";
    echo "\t" . '</div>' . "\n";
  
  echo '</div>' . "\n\n";
}

include('gsoc/footer.html');

?>
