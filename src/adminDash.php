<?php
require_once 'init.php';

$page_access_level = 'admin';
require_once 'securityCheck.php';

# fetch unapproved rotten users
function fetch_unapproved_rotten_users() {
  $sql = "SELECT
            mbr_username,
            f_name, 
            l_name
          FROM account
          WHERE acc_type = 'rotten' AND mbr_approved = 0
          ORDER BY f_name ASC";
  return db_query($sql);
}

# fetch unapproved public reviews
function fetch_unapproved_public_reviews() {
  $sql = "SELECT
            review_id,
            rating,
            review_comment,
            m.title
          FROM review r
          LEFT JOIN movie m ON r.movie_id = m.movie_id
          LEFT JOIN account a ON r.mbr_username = a.mbr_username
          WHERE public_approved = 0 AND a.acc_type = 'rotten'
          ORDER BY title ASC";
  return db_query($sql);
}

# fetch preview movies
function fecth_preview_movies() {
  $sql = "SELECT
            preview_id,
            theater,
            date,
            time,
            m.title
          FROM preview p
          LEFT JOIN movie m ON p.movie_id = m.movie_id
          ORDER BY date, time, title, theater ASC";
  return db_query($sql);
}

$message = "Approval is success.";

# update rotten users approval
if (isset($_POST["rotten_approval"])) {
  $rotten_approval = $_POST["rotten_approval"];

  foreach ($rotten_approval as &$user) {
    $sql = "UPDATE account SET mbr_approved = 1 WHERE mbr_username = '$user'";
    db_query($sql);
  }

  $rotten_message = $message;
  $approve_rotten_result = fetch_unapproved_public_reviews();
}

# update public review approval
if (isset($_POST["review_approval"])) {
  $review_approval = $_POST["review_approval"];

  foreach ($review_approval as &$movie) {
    $sql = "UPDATE review SET public_approved = 1 WHERE review_id = '$movie'";
    db_query($sql);
  }

  $review_message = $message;
  $approve_review_result = fetch_unapproved_rotten_users();
}
?>

<!DOCTYPE html>
<html>
<head><title>Admin at Rotten Cucumbers</title></head>

<style>
table, td, th {  
  border: 1px solid #ddd;
  text-align: center;
}

table {
  border-collapse: collapse;
  width: 30%;
}

th, td {
  padding: 10px;
}

p {
  text-align: right;
}
</style>

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
        <a href="previewForm.php">Create New Preview for Movie</a>
    </div>
    <div style="text-align: right;">
        <?=$_SESSION["fullname"]?> is Signed In.
    </div>
</div>
<hr>

<h1>Administrator Dashboard - Rotten Cucumbers</h1>
You are signed in as an Administrator, but can still access regular member pages from the top.
<br><br>

<h2>List of users that need approval to become rotten reviewers:</h2>
  <?
  $approve_rotten_result = fetch_unapproved_rotten_users();
  $total_rows = $approve_rotten_result->num_rows;
  if ($total_rows > 0) {
  ?>
    <form action="adminDash.php" method="POST" id="rotten_form">
      <table>
        <tr>
          <th>Approval</th>
          <th>First Name</th>
          <th>Last Name</th>
        </tr>
        <?
        while ($rotten_row = $approve_rotten_result->fetch_assoc()) {
        ?>
          <tr>
            <td><input type="checkbox" name="rotten_approval[]" value=<?echo $rotten_row["mbr_username"]?>></td>
            <td><?echo $rotten_row["f_name"]?></td>
            <td><?echo $rotten_row["l_name"]?></td>
          </tr>
        <?
        } ?>
      </table>
      <br><br>
      
      <input type="submit" value="Approve">
      &nbsp;&nbsp;
      <input type="reset" value="Clear">
      <br><br>
    </form>
  <?
  }
  else {
    echo "<br><br>No users that need approval to become rotten reviewers found.<br><br>";
  }
  ?>

<? if (isset($rotten_message)) { ?>
  <h3><?= $rotten_message ?></h3>
  <br><br>
<? 
} ?>

<h2>List of movie ratings that need approval before becoming public:</h2>
<?
  $approve_review_result = fetch_unapproved_public_reviews();
  $total_rows = $approve_review_result->num_rows;
  if ($total_rows > 0) {
  ?>
    <form action="adminDash.php" method="POST" id="review_form">
      <table>
        <tr>
          <th>Approval</th>
          <th>Title</th>
          <th>Rating</th>
          <th>Comment</th>
        </tr>
        <?
        while ($review_row = $approve_review_result->fetch_assoc()) {
        ?>
          <tr>
            <td><input type="checkbox" name="review_approval[]" value=<?echo $review_row["review_id"]?>></td>
            <td><?echo $review_row["title"]?></td>
            <td><?echo $review_row["rating"]?></td>
            <td><?echo $review_row["review_comment"]?></td>
          </tr>
        <?
        }
        ?>
      </table>
      <br><br>

      <input type="submit" value="Approve">
      &nbsp;&nbsp;
      <input type="reset" value="Clear">
      <br><br>
    </form>
  <?
  }
  else {
    echo "<br><br>No movies that need approval to become public.<br><br>";
  }
  ?>

<? if (isset($review_message)) { ?>
    <h3><?= $review_message ?></h3>
    <br><br>
<? } ?>

<h2>List of all movies scheduled previews in the database:</h2>
  <?
  $preview_result = fecth_preview_movies();
  $total_rows = $preview_result->num_rows;
  if ($total_rows > 0) {
  ?>
    <table>
      <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Title</th>
        <th>Theater</th>
      </tr>
      <?
      while ($preview_row = $preview_result->fetch_assoc()) {
      ?>
        <tr>
          <td><?php echo date("m/d/Y", strtotime($preview_row["date"])); ?></td>
          <td><?php echo date("h:i a", strtotime($preview_row["time"])); ?></td>
          <td><?echo $preview_row["title"]?></td>
          <td><?echo $preview_row["theater"]?></td>
        </tr>
      <?
      }
      ?>
    </table>
  <?
  }
  else {
    echo "<br><br>No movies in the database found.<br><br>";
  }
  ?>

</body>
</html>