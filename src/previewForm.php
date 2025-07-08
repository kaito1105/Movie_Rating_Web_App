<?
require_once 'init.php';
$page_access_level = 'admin';
require_once 'securityCheck.php';

# check if supplying the correct input
if (isset($_POST["title"])) {
  $movie_id = trim($_POST["title"]);
  $theater = trim($_POST["theater"]);
  $date = trim($_POST["date"]);
  $time = trim($_POST["time"]);

  # invalid input
  if ($movie_id == '-1' || $theater == '' || $date == '' || $time == '') {
    $message = "Insufficient Data Supplied";
  }
  # invalid input
  else if (new DateTime("$date $time") <= new DateTime()) {
    $message = "Past preview cannot be set!";
  }
  # valid input
  else {
    $sql = "SELECT *
                FROM preview
                WHERE movie_id = '$movie_id' AND theater = '$theater' AND date = '$date' AND time = '$time'";
    $result = db_query($sql);

    if ($result->num_rows > 0) {
      $message = "Preview Already Exists!";
    } else {
      $sql = "INSERT INTO preview(theater, date, time, movie_id) values ('$theater','$date', '$time', '$movie_id')";
      db_query($sql);
      $success_message = "Preview added successfully!";
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>New Preview Form</title>
</head>

<body>
  <hr>
  <div style="display: grid; grid-template-columns: auto auto; align-items: center;">
    <div>
      <a href="index.php">Home</a>
      &nbsp;&nbsp;
      <a href="securityCheck.php?log_out=yes">Sign out</a>
      &nbsp;&nbsp;
      <a href="popcornDash.php">Popcorn Home Page</a>
      &nbsp;&nbsp;
      <a href="rottenDash.php">Rotten Home Page</a>
      &nbsp;&nbsp;
      <a href="adminDash.php">Main Admin Page</a>
    </div>
    <div style="text-align: right;">
      <?= $_SESSION["fullname"] ?> is Signed In.
    </div>
  </div>
  <hr>

  <h1>Create a Preview for Movie</h1>

  <? if (isset($message)) { ?>
    <h2><?= $message ?></h2>
    <h3>Please Try Again</h3>
  <?
  } else if (isset($success_message)) { ?>
      <h2><?= $success_message ?></h2>
  <?
  } else { ?>
      <h3>Please enter the movie information below</h3>
  <?
  } ?>

  <form action="previewForm.php" method="POST" id="preview_form">
    Movie:
    <select name="title" id="title">
      <option value="-1">Choose Movie</option>
      <?
      $movie_sql = "SELECT * FROM movie ORDER BY title ASC";
      $movie_result = db_query($movie_sql);

      while ($movie_row = $movie_result->fetch_assoc()) {
        ?>
        <option value="<? echo $movie_row["movie_id"]; ?>">
          <? echo $movie_row["title"]; ?>
        </option>
      <?
      }
      ?>
    </select>
    <br><br>
    Theater: <input type="text" name="theater">
    <br><br>
    Date: <input type="date" name="date">
    <br><br>
    Time: <input type="time" name="time">
    <br><br>

    <input type="submit" value="Create">
    &nbsp;&nbsp;
    <input type="reset" value="Clear">
    <br><br>
  </form>

</body>

</html>