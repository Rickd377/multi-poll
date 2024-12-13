<?php
include 'db-conn.php';

$poll_id = $_GET["id"];
$ip_address = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT title, total_votes FROM polls WHERE id = $poll_id";
$result = $conn->query($sql);
$poll = $result->fetch_assoc();

$sql = "SELECT id, option_text, votes FROM options WHERE poll_id = $poll_id";
$options = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="style.css">
  <title>Poll</title>
</head>
<body class="poll-item-body">
  <a href="index.php" class="backarrow"><i class="fa-solid fa-angle-left"></i></a>
  <form action="poll.php?id=<?php echo $poll_id; ?>" method="post" class="poll-item-wrapper">
    <h2 class="title-text title-poll-item"><?php echo $poll["title"]; ?></h2>
    <?php
    if ($options->num_rows > 0) {
      while($option = $options->fetch_assoc()) {
        $percentage = $poll['total_votes'] > 0 ? ($option["votes"] / $poll['total_votes']) * 100 : 0;
        echo '<div class="input-wrapper-options">';
        echo '<input type="radio" name="option" id="option-' . $option["id"] . '" value="' . $option["id"] . '">';
        echo '<label for="option-' . $option["id"] . '">' . $option["option_text"] . '</label>';
        echo '<div class="score"><div class="score-bar" style="width:' . $percentage . '%"></div></div>';
        echo '</div>';
      }
    } else {
      echo "No options available.";
    }
    ?>
    <button type="submit" class="poll-submit-btn">Submit</button>
  </form>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sql = "SELECT * FROM votes WHERE poll_id = $poll_id AND ip_address = '$ip_address'";
  $result = $conn->query($sql);

  if ($result->num_rows == 0) {
    $option_id = $_POST["option"];
    $sql = "UPDATE options SET votes = votes + 1 WHERE id = $option_id";
    $conn->query($sql);

    $sql = "UPDATE polls SET total_votes = total_votes + 1 WHERE id = $poll_id";
    $conn->query($sql);

    $sql = "INSERT INTO votes (poll_id, ip_address) VALUES ($poll_id, '$ip_address')";
    $conn->query($sql);

    header("Location: poll.php?id=$poll_id");
  } else {
    echo "<script>alert('You have already voted on this poll.');</script>";
  }
}
$conn->close();
?>