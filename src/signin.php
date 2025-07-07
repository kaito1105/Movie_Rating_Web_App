<?php
require_once 'init.php';

if (isset($_SESSION["userid"])) {
  // They are already signed in, so cant be on this page
  if ($_SESSION["is_approved"] == -1) {
    header("Location: adminDash.php");
  }
  else if ($_SESSION["is_approved"] == 1) {
    header("Location: rottenDash.php");
  }
  else {
    header("Location: popcornDash.php");
  }
  exit;
}
else if (isset($_GET["userid"])) {
  // login form submitted
  $userid = trim($_GET["userid"]);
  $password = trim($_GET["password"]);
  $sql = "SELECT * FROM account
            WHERE mbr_username = '$userid' AND acc_password= '$password'";
  $result = db_query($sql);

  if($result->num_rows > 0) {
    // Should only be one row since enforcing unique user names in acct signup
    $row = $result->fetch_assoc();

    //Set session variables
    $_SESSION["userid"] = $userid;
    $_SESSION["fullname"] = $row["f_name"] . " " . $row["l_name"];
    $_SESSION["is_approved"] = $row["mbr_approved"];
    
    //Jump to each dashboard
    if ($row["mbr_approved"] == -1) {
      header("Location: adminDash.php");
    }
    else if ($row["mbr_approved"] == 1) {
      header("Location: rottenDash.php");
    }
    else {
      header("Location: popcornDash.php");
    }
    exit;
  }
  else {
    $message = "Login Attempt Failed. Please Try Again";  // fall back through to form with message
  }
}

if (isset($_GET['message'])) {
  switch ($_GET['message']){
    case 'account_created':
        $message = 'Your Account was created';
        break;
    case 'not_signed_in':
        $message = 'You must be Signed in to Access that Page.';
        break;
    case 'admin_access_only':
        $message = 'Only Administrators can Access that Page.';
        break;
    case 'logged_out':
        $message = 'You have been Logged Out. Thank you for using Rotten Cucumbers.';
        break;
    default:
  }
}
?>

<!DOCTYPE html>
<html>
<head><title>Rotten Cucumbers</title></head>

<body>
<hr>
<a href="index.php">Home</a>
<hr>

<? 
if (isset($message)) { ?>
  <h1><?= $message ?></h1>
<? 
} else { ?>
  <h1>Please Sign In to Access the Rotten Cucumbers</h1>
<?
} ?>

<form action="signin.php" method="GET">
    Username: <input type="text" name="userid">
    <br><br>
    Password: <input type="password" name="password">
    <br><br>

    <input type="submit" value="Sign In">
    &nbsp;&nbsp;
    <input type="reset" value="Clear">
    <br><br>

    Don't have an account yet? <a href="signup.php">Sign up here</a>.
</form>

</body>
</html>