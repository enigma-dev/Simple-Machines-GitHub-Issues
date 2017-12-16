<?php
include('common.php');
include('list_tickets.php');
include('view_ticket.php');
include('new_ticket.php');

if (!isset($_REQUEST['id']) && !isset($_REQUEST['new'])) {
  display_ticket_list(isset($_REQUEST['closed']));
}
else if (!isset($_REQUEST['new'])) {
  display_single_ticket($_REQUEST['id']);
}
else {
  display_new_ticket_form();
}

?>

