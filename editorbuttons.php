<?php

  $formats = array('Bold','Italic','URL');
  
  foreach ($formats as $format) echo '<button class="formatbutton" type="button" onclick="doFormat' . $format . '()">' . $format . '</button>';
  
?>

<script type="text/javascript">
function surroundSelection(pre,post) {
  var ta = document.getElementById("commentfield");
  if (ta != null) {
    var txt = ta.value, ss = ta.selectionStart, se = ta.selectionEnd;
    ta.value = txt.substr(0,ss) + pre + txt.substring(ss,se) + post + txt.substr(se);
    ta.selectionEnd = se + pre.length;
    ta.selectionStart = ss + pre.length;
    ta.focus();
  }
}
function doFormatBold() {
  surroundSelection("**","**");
  return false;
}
function doFormatItalic() {
  surroundSelection("*","*");
  return false;
}
function doFormatURL() {
  var ta = document.getElementById("commentfield");
  if (ta != null) {
    var txt = ta.value, ss = ta.selectionStart, se = ta.selectionEnd;
    var url = prompt("Enter the URL to point to:", "www.google.com");
    var pgname = (ss != se)? txt.substring(ss,se): prompt("Enter the text to display for this link", url);
    ta.value = txt.substr(0,ss) + "![" + pgname + "](" + url + ")" + txt.substr(se);
    ta.selectionStart = ss + 2;
    ta.selectionEnd = ta.selectionStart + pgname.length;
    ta.focus();  
  }
  return false;        
}
</script>
