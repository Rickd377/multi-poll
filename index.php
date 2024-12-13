<?php
include 'db-conn.php';

$sql = "SELECT id, title, total_votes FROM polls";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Polls</title>
</head>
<header>
  <h1>Pick a Poll to Vote</h1>
  <a href="add-poll.php">Add a Poll</a>
</header>
<body class="home-body">
  <div class="poll-container">
    <?php
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo '<a href="poll.php?id=' . $row["id"] . '" class="poll">';
        echo '<h3 class="poll-title title-text">' . $row["title"] . '</h3>';
        echo '<p class="vote-amount">Votes: ' . $row["total_votes"] . '</p>';
        echo '</a>';
      }
    } else {
      echo "No polls available.";
    }
    $conn->close();
    ?>
  </div>
</body>
</html>