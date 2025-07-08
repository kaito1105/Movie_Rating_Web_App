<?php
require_once 'init.php';

// is form being submitted
if (isset($_POST["userid"])) {
  $fname = trim($_POST["fname"]);
  $lname = trim($_POST["lname"]);
  $userid = trim($_POST["userid"]);
  $password = trim($_POST["password1"]);

  if (isset($_POST["acctype"]) && is_array($_POST["acctype"])) {
    $acctype = array_map('trim', $_POST["acctype"]);
  } else {
    $acctype = ['popcorn'];
  }
  if ($fname == '' || $lname == '' || $userid == '' || $password == '') {
    $message = "Insufficient Data Supplied"; // fall back through to form with message
  } else {
    //check to see if user already exists
    $sql = "SELECT * FROM account WHERE mbr_username = '$userid' ";
    $result = db_query($sql);

    if ($result->num_rows > 0) {
      $message = "Username Already Exists!"; // fall back through to form with message
    } else {
      $sql = "INSERT INTO account(mbr_username, f_name, l_name, acc_type, acc_password, mbr_approved) values ('$userid','$fname','$lname', '$acctype[0]','$password',0)";
      $result = db_query($sql);
      // transfer to login page
      header("Location: signin.php?message=account_created");
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Sign up for the Rotten Cucumbers</title>
</head>
<script>
  function checkPasswords() {
    var pass1 = document.getElementById('password1').value.trim();
    var pass2 = document.getElementById('password2').value.trim();
    if (pass1 != pass2) {
      window.alert('The passwords do not match!');
      return false;  // abort form submit
    }
    return true; // submit form
  }
</script>

<body>
  <hr>
  <a href="index.php">Home</a>
  <hr>

  <h1>Create an Account for the Rotten Cucumbers</h1>

  <? if (isset($message)) { ?>
    <h2><?= $message ?></h2>
    <h3>Please Try Again</h3>
  <?
  } else { ?>
    <h3>Please enter your information below</h3>
    <?
  } ?>

  <form action="signup.php" method="POST" id="account_form" onsubmit="return checkPasswords()">
    First name: <input type="text" name="fname">
    <br><br>
    Last name: <input type="text" name="lname">
    <br><br>
    Account type:
    <input type="checkbox" name="acctype[]" value="rotten"> Rotten Reviewer
    <br><br>
    User name: <input type="text" name="userid">
    <br><br>
    Password: <input type="password" name="password1" id="password1">
    <br><br>
    Re-type Password: <input type="password" name="password2" id="password2">
    <br><br>

    <input type="submit" value="Sign Up">
    &nbsp;&nbsp;
    <input type="reset" value="Clear">
    <br><br>

    Already have account? <a href="signin.php">Sign in here</a>.

  </form>
</body>

</html>