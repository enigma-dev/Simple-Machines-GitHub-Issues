<?php
require_once('../forums/SSI.php'); // This must point to your forums' SSI.php. It has to be the first thing included.
include('../site/template.php');  // In our website, this is the basic template that appears around the tracker. Yours may be different. Include it here.
require_once('config.php'); // This is the basic configuration file you've edited, we hope.
echo "<LINK href=\"issues.css\" rel=\"stylesheet\" type=\"text/css\">"; // The issues stylesheet.
?>
