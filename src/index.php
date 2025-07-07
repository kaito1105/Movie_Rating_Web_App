<?
require_once 'init.php';

# set random movies
$random_movies_sql = "WITH RandomMovies AS (
                        SELECT movie_id, title
                        FROM movie 
                        ORDER BY RAND() 
                        LIMIT 2
                        ),
                        RandomComments AS (
                          SELECT 
                              re.movie_id, 
                              re.review_comment
                          FROM review re
                          INNER JOIN account acc ON re.mbr_username = acc.mbr_username
                          WHERE acc.acc_type = 'rotten' AND acc.mbr_approved = 1 AND re.public_approved = 1
                          ORDER BY RAND()
                        )
                      SELECT
                        rm.title, 
                        COALESCE(ROUND(AVG(CASE WHEN a.acc_type = 'popcorn' THEN r.rating END), 2), 'Not Rated') AS avg_popcorn_rating,
                        COALESCE(ROUND(AVG(CASE WHEN a.acc_type = 'rotten' AND mbr_approved = 1 AND r.public_approved = 1 THEN r.rating END), 2), 'Not Rated') AS avg_rotten_rating,
                        COALESCE((
                          SELECT rc.review_comment
                            FROM RandomComments rc
                            WHERE rc.movie_id = rm.movie_id
                            LIMIT 1), 'No Comment'
                        ) AS rotten_comment
                      FROM RandomMovies rm
                      LEFT JOIN review r ON rm.movie_id = r.movie_id
                      LEFT JOIN account a ON r.mbr_username = a.mbr_username
                      LEFT JOIN movie_genre mg ON rm.movie_id = mg.movie_id
                      LEFT JOIN genre g ON mg.genre_id = g.genre_id
                      GROUP BY rm.movie_id";
$random_movies_result = db_query($random_movies_sql);

$title = '';

# search movies given conditions
if (isset($_GET["title"])) {
  $title = trim($_GET["title"]);
  $genre = trim($_GET["genre"]);
  $search_where_clause = "1";

  if ($title != '') {
    $search_where_clause .= " AND title LIKE '%$title%' ";
  }
  if ($genre != '-1') {
    $search_where_clause .= " AND g.genre_id = $genre ";
  }
  
  # set search movies
  $search_movies_sql = "SELECT
                          m.title, 
                          GROUP_CONCAT(DISTINCT g.gen_name ORDER BY g.gen_name ASC SEPARATOR ', ') AS genres, 
                          m.release_year,
                          GROUP_CONCAT(DISTINCT d.dir_name ORDER BY d.dir_name ASC SEPARATOR ', ') AS directors,
                          GROUP_CONCAT(DISTINCT actor.act_name ORDER BY actor.act_name ASC SEPARATOR ', ') AS actors,
                          COALESCE(ROUND(AVG(CASE WHEN a.acc_type = 'popcorn' THEN r.rating END), 2), 'Not Rated') AS avg_popcorn_rating,
                          COALESCE(ROUND(AVG(CASE WHEN a.acc_type = 'rotten' AND mbr_approved = 1 AND r.public_approved = 1 THEN r.rating END), 2), 'Not Rated') AS avg_rotten_rating,
                          COALESCE((
                            SELECT re.review_comment
                            FROM review re
                            INNER JOIN account acc ON re.mbr_username = acc.mbr_username
                            WHERE re.movie_id = m.movie_id AND acc.acc_type = 'rotten' AND mbr_approved = 1 AND re.public_approved = 1
                            ORDER BY RAND()
                            LIMIT 1), 'No Comment'
                          ) AS rotten_comment
                        FROM movie m
                        LEFT JOIN review r ON m.movie_id = r.movie_id
                        LEFT JOIN account a ON r.mbr_username = a.mbr_username
                        LEFT JOIN movie_genre mg ON m.movie_id = mg.movie_id
                        LEFT JOIN genre g ON mg.genre_id = g.genre_id
                        LEFT JOIN movie_director md ON m.movie_id = md.movie_id
                        LEFT JOIN director d ON md.director_id = d.director_id
                        LEFT JOIN movie_actor ma ON m.movie_id = ma.movie_id
                        LEFT JOIN actor ON  ma.actor_id = actor.actor_id
                        WHERE $search_where_clause 
                        GROUP BY m.movie_id";
  $search_result = db_query($search_movies_sql);

  # no movies
  if ($search_result->num_rows == 0) {
    $message = 'No Movies Matched your Search Criteria.';
    unset($search_result);
  }
}
?>

<!DOCTYPE html>
<html>
<head><title>Rotten Cucumbers</title></head>

<style>
table, td, th {  
  border: 1px solid #ddd;
  text-align: center;
}

table {
  border-collapse: collapse;
  width: 90%;
}

th, td {
  padding: 10px;
}
</style>

<body>
<hr>
<div style="display: grid; grid-template-columns: auto auto; align-items: center;">
  <div>
  <?
    if (isset($_SESSION["userid"])) { ?>
      <a href="securityCheck.php?log_out=yes">Sign out</a>

      <?
      if ($_SESSION["is_approved"] == -1) { ?>
        &nbsp;&nbsp;
        <a href="popcornDash.php">Popcorn Home Page</a>
        &nbsp;&nbsp;
        <a href="rottenDash.php">Rotten Home Page</a>
        &nbsp;&nbsp;
        <a href="adminDash.php">Main Admin Page</a>
      <?
      } else if ($_SESSION["is_approved"] == 1) { ?>
        &nbsp;&nbsp;
        <a href="rottenDash.php">Rotten Home Page</a>
      <?
      } else { ?>
        &nbsp;&nbsp;
        <a href="popcornDash.php">Popcorn Home Page</a>
      <?
      } ?>
    <?
    } else { ?>
      <a href="signin.php">Sign in</a>
    <?
    } ?>
  </div>
  <div style="text-align: right;">
    <?
    if (isset($_SESSION["userid"])) { ?>
      <?=$_SESSION["fullname"]?> is Signed In.
    <?
    } ?>
  </div>
</div>
<hr>

<h1>Welcome to the Rotten Cucumbers</h1><br>
  <h2>Introduction to a few Movies:</h2>
    <?
    $total_rows = $random_movies_result->num_rows;
    if ($total_rows > 0) {
    ?>
      <table>
        <tr>
          <th>Title</th>
          <th>Average Popcorn Rate</th>
          <th>Average Rotten Rate</th>
          <th>Comment</th>
        </tr>
        <?
        while ($movie_row = $random_movies_result->fetch_assoc()) {
        ?>
          <tr>
            <td><?echo $movie_row["title"]?></td>
            <td><?echo $movie_row["avg_popcorn_rating"]?></td>
            <td><?echo $movie_row["avg_rotten_rating"]?></td>
            <td><?echo $movie_row["rotten_comment"]?></td>
          </tr>
        <?
        }
        ?>
      </table>
    <?
    }
    else {
        echo "<br><br>No movies found for Rutten Cucumbers.<br><br>";
    }
    ?>
    <br><br>

  <h2>Search Movies:</h2>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
      Title: <input type="text" name="title"  value="<?= $title ?>">
      <br><br>
      Genre:
      <select name="genre" id="genre">
        <option value="-1">Choose Genre</option>
        <?
        $genre_sql = "SELECT * FROM genre ORDER BY gen_name ASC";
        $genre_result = db_query($genre_sql);
        if (isset($_GET['clear'])) {
          $selected_genre = '-1';
        } else {
          $selected_genre = $_GET['genre'] ?? '-1';
        }
        while ($genre_row = $genre_result->fetch_assoc()) {
            $is_selected = ($genre_row["genre_id"] == $selected_genre) ? 'selected' : '';
        ?>
          <option value="<?php echo $genre_row["genre_id"]; ?>" <?php echo $is_selected; ?>>
              <? echo $genre_row["gen_name"]; ?>
          </option>
        <?
        }
        ?>
      </select>
      <br><br>
      <input type="submit" value="Search">
      &nbsp;&nbsp;
      <input type="reset" value="Clear" onclick="location.href='<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>?clear=1'">
    </form>

    <? 
    if (isset($message)) { ?>
    <h3><?= $message ?></h3>
    <? } 
    ?>

  <? 
  if (isset($search_result) && ($title != '' || $genre !='-1')) { ?>
    <h2>Search Results</h2>
      <table>
        <tr>
          <th>Title</th>
          <th>Genre</th>
          <th>Release Year</th>
          <th>Director</th>
          <th>Actor</th>
          <th>Average Popcorn Rate</th>
          <th>Average Rotten Rate</th>
          <th>Comment</th>
        </tr>

        <? 
        while ($search_row = $search_result->fetch_assoc()) { ?>
          <tr>
            <td><?echo $search_row["title"]?></td>
            <td><?echo $search_row["genres"]?></td>
            <td><?echo $search_row["release_year"]?></td>
            <td><?echo $search_row["directors"]?></td>
            <td><?echo $search_row["actors"]?></td>
            <td><?echo $search_row["avg_popcorn_rating"]?></td>
            <td><?echo $search_row["avg_rotten_rating"]?></td>
            <td><?echo $search_row["rotten_comment"]?></td>
          </tr>
        <? 
        } ?>
      </table>
  <? 
  } ?>
  
</body>
</html>