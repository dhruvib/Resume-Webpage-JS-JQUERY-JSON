<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','dhruvi','dhruvi');
session_start();
$stmt = $pdo->query("SELECT first_name, headline, profile_id FROM profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
?>

<html>
<head>
  <title>Dhruvi Bhagat's Index Page</title>
  <style>
  body {font-family: Arial, Helvetica, sans-serif;}
  form {border: 3px solid #f1f1f1;}

  table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 70%;
  border: 1px solid #ddd;
}

th, td {
  text-align: left;
  padding: 16px;
}

.na{
  background-color: #d3d3d3
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

button:hover {
  opacity: 0.8;
}
  </style>
</head>
<body>
  <h1>Dhruvi Bhagat's Resume Registry</h1>
  <table border="1">
    <tr>
    <td class = "na">Name</td>
    <td class = "na">Headline</td>
    <?php
    if(isset($_SESSION['user_id'])){
      echo("<td class = 'na'>");
      echo ("Action");
      echo("</td>");
    }
    ?>
    </tr>
  <?php
  foreach ( $rows as $row ) {
      echo "<tr><td>";
      echo ('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'])."</a>");
      echo("</td><td>");
      echo(htmlentities($row['headline']));
      echo("</td>");
      if(isset($_SESSION['user_id'])){
        echo("<td>");
        echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
        echo ('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
        echo("</td>");
      }
      echo("</tr>\n");
  }
  if(!isset($_SESSION['user_id'])){
    echo ('<a href = "login.php">Please Login</a><br>');
  }else{
    echo ('<a href = "add.php">Add new entry</a><br>');
    echo ('<a href = "logout.php">Logout</a>');
  }
  ?>
</body>
</html>
