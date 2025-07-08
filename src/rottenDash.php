<?php
require_once 'init.php';

$page_access_level = 'professional_user';
require_once 'securityCheck.php';

$username = $_SESSION['userid'];

// Fetch movies for the dropdown
$movies_result = $db_conn->query("SELECT movie_id, title FROM movie");

// Fetch genres for the multi-select menu
$genres_result = $db_conn->query("SELECT genre_id, gen_name FROM genre");

// Fetch reviews entered by the Rotten Reviewer
function fetch_rotten_review()
{
    $username = $_SESSION['userid'];
    $reviews_result = db_query("SELECT r.review_id, m.title, r.rating, r.review_comment, 
                                                GROUP_CONCAT(DISTINCT g.gen_name ORDER BY g.gen_name ASC SEPARATOR ', ') AS movie_genres
                                            FROM review r
                                            JOIN movie m ON r.movie_id = m.movie_id
                                            JOIN movie_genre mg ON mg.movie_id = m.movie_id
                                            JOIN genre g ON mg.genre_id = g.genre_id
                                            WHERE r.mbr_username = '$username'
                                            GROUP BY r.review_id, m.title, r.rating, r.review_comment");
    return $reviews_result;
}

// Handle form submission
if (isset($_POST['submission'])) {
    $movie_id = $_POST['movie'];
    $rating = $_POST['rating'];
    $genres = isset($_POST['genres']) ? $_POST['genres'] : [];
    $comment = $_POST['comment'] ?? '';

    // Validate input
    if ($movie_id == "-1" || !$rating || !is_numeric($rating) || $rating < 1 || $rating > 10) {
        $error_message = "<p style='color: red;'>Invalid input. Please ensure all required fields are completed.</p>";
    } else {
        // Check for duplicate review
        $stmt = $db_conn->prepare("SELECT COUNT(*) FROM review WHERE mbr_username = ? AND movie_id = ?");
        $stmt->bind_param("si", $username, $movie_id);
        $stmt->execute();
        $stmt->bind_result($review_count);
        $stmt->fetch();
        $stmt->close();

        if ($review_count > 0) {
            $error_message = "<p style='color: red;'>You have already submitted a review for this movie.</p>";
        } else {
            // Insert review into the review table
            $stmt = $db_conn->prepare("INSERT INTO review (mbr_username, movie_id, rating, review_comment) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $username, $movie_id, $rating, $comment);
            $stmt->execute();
            $stmt->close();

            // Insert genres into movie_genre
            if (!empty($genres)) {
                $genres = array_map('intval', $genres);

                foreach ($genres as $genre_id) {
                    // Check for duplicate genre association
                    $stmt = $db_conn->prepare("SELECT COUNT(*) FROM movie_genre WHERE genre_id = ? AND movie_id = ?");
                    $stmt->bind_param("ii", $genre_id, $movie_id);
                    $stmt->execute();
                    $stmt->bind_result($genre_count);
                    $stmt->fetch();
                    $stmt->close();

                    if ($genre_count == 0) {
                        // Insert if not a duplicate
                        $stmt = $db_conn->prepare("INSERT INTO movie_genre (genre_id, movie_id) VALUES (?, ?)");
                        $stmt->bind_param("ii", $genre_id, $movie_id);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }

            $message = "<p style='color: green;'>Review submitted successfully!</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotten Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            margin-bottom: 40px;
        }

        textarea {
            width: 100%;
            height: 80px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <hr>
    <div style="display: grid; grid-template-columns: auto auto; align-items: center;">
        <div>
            <a href="index.php">Home</a>
            &nbsp;&nbsp;
            <a href="securityCheck.php?log_out=yes">Sign out</a>

            <?
            if ($_SESSION["is_approved"] == -1) { ?>
                &nbsp;&nbsp;
                <a href="popcornDash.php">Popcorn Home Page</a>
                &nbsp;&nbsp;
                <a href="adminDash.php">Main Admin Page</a>
            <?
            } ?>

        </div>
        <div style="text-align: right;">
            <?= $_SESSION["fullname"] ?> is Signed In.
        </div>
    </div>
    <hr>

    <h1>Rotten Dashboard - Rotten Cucumbers</h1>

    <? if (isset($error_message)) { ?>
        <h2><?= $error_message ?></h2>
    <?
    } else { ?>
        <h2>Add a Rotten Review</h2>
    <?
    } ?>

    <form method="POST" action="">
        <label for="movie">Select Movie:</label>
        <select name="movie" id="movie" required>
            <option value="-1">Choose Movie</option>
            <?php while ($movie = $movies_result->fetch_assoc()): ?>
                <option value="<?= $movie['movie_id'] ?>"><?= htmlspecialchars($movie['title']) ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="rating">Rating (1-10):</label>
        <input type="range" name="rating" id="rating" min="1" max="10" value="5"
            oninput="this.nextElementSibling.value = this.value">
        <output>5</output>
        <br><br>

        <label for="genres">Select Genres (optional):</label><br>
        <select name="genres[]" id="genres" multiple>
            <?php while ($genre = $genres_result->fetch_assoc()): ?>
                <option value="<?= $genre['genre_id'] ?>"><?= htmlspecialchars($genre['gen_name']) ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="comment">Comment (optional):</label>
        <textarea name="comment" id="comment" placeholder="Enter your thoughts..."></textarea>
        <br><br>

        <button type="submit" name="submission">Submit Review</button>
    </form>

    <? if (isset($message)) { ?>
        <h2><?= $message ?></h2><br>
    <?
    } ?>

    <h2>Your Reviews:</h2>
    <?php
    $reviews_result = fetch_rotten_review();
    if ($reviews_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Movie</th>
                    <th>Rating</th>
                    <th>Genre</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($review = $reviews_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($review['title']) ?></td>
                        <td><?= htmlspecialchars($review['rating']) ?></td>
                        <td><?= htmlspecialchars($review['movie_genres']) ?></td>
                        <td><?= htmlspecialchars($review['review_comment']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No reviews found.</p>
    <?php endif; ?>
</body>

</html>

<?php
$db_conn->close();
?>