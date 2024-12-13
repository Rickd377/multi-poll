<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include 'db-conn.php';

  $title = $_POST["title"];
  $options = $_POST["option"];

  $sql = "INSERT INTO polls (title) VALUES ('$title')";
  if ($conn->query($sql) === TRUE) {
    $poll_id = $conn->insert_id;
    foreach ($options as $option) {
      $sql = "INSERT INTO options (poll_id, option_text) VALUES ('$poll_id', '$option')";
      $conn->query($sql);
    }
    $message = "New poll created successfully";
  } else {
    $message = "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="style.css">
  <title>Add Poll</title>
</head>
<body class="add-poll-body">
  <a href="index.php" class="backarrow"><i class="fa-solid fa-angle-left"></i></a>
  <form action="add-poll.php" method="post" class="add-poll-container">
    <h2 class="addapoll">Add a Poll</h2>
    <p class="message"><?php echo htmlspecialchars($message)?></p>
    <div class="input-wrapper first-child">
      <input type="text" name="title" id="title" class="title-poll-input" placeholder="Question..." required>
    </div>
    <div class="input-wrapper">
      <input type="text" name="option[]" id="option" class="option-poll-input" placeholder="Option..." required>
    </div>
    <div id="additional-options"></div>
    <p class="add-more" onclick="addOption()">+ Add More</p>
    <button type="submit" class="submit-button">Add Poll</button>
  </form>
  <script>
    function addOption() {
      const div = document.createElement('div');
      div.className = 'input-wrapper';
      div.innerHTML = '<input type="text" name="option[]" class="option-poll-input" placeholder="Option...">';
      document.getElementById('additional-options').appendChild(div);
    }
  </script>
</body>
</html>