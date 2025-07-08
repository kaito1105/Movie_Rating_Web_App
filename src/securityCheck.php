<?php
// This file does not generate an HTML page.
// Require AFTER init.php file ONLY in Pages that require login security (not signup or login pages)

if (isset($_GET["log_out"])) {
  // Request to Log Out
  // Wipe login session and fall send to login form with a message
  session_start();
  session_destroy();

  header("Location: signin.php?message=logged_out");
  exit;
}

if (!isset($_SESSION["userid"])) {
  // OOPS, must be logged in to view this page
  header("Location: index.php?message=not_signed_in");
  exit;
}

if ($page_access_level == 'admin' && $_SESSION["is_approved"] == 0) {
  // OOPS, this page is for admins only - send to popcorn home page
  header("Location: popcornDash.php");
  exit;
}

if ($page_access_level == 'admin' && $_SESSION["is_approved"] == 1) {
  // OOPS, this page is for admins only - send to rotten home page
  header("Location: rottenDash.php");
  exit;
}

if ($page_access_level == 'user' && $_SESSION["is_approved"] == 1) {
  // OOPS, this page is for general users only - send to rotten home page
  header("Location: rottenDash.php");
  exit;
}

if ($page_access_level == 'professional_user' && $_SESSION["is_approved"] == 0) {
  // OOPS, this page is for professional users only - send to popcorn home page
  header("Location: popcornDash.php");
  exit;
}

if (($page_access_level == 'user' || $page_access_level == 'professional_user') && $_SESSION["is_approved"] == -1) {
  // For simplicity, we'll let admins into user pages, but could easily restrict that.
}

?>