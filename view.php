<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','dhruvi','dhruvi');
session_start();

if(isset($_POST['done'])){
  header("Location: index.php");
  return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name, email, headline, summary FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<head>
  <style>
  body {font-family: Arial, Helvetica, sans-serif;}

  input[type=text], input[type=password] {
  width: 40%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  }

  .done{
  width: 20%;
  color: white;
  padding: 10px 18px;
  margin: 8px 0;
  border: none;
  background-color: #6495ed;
  }

  button:hover {
  opacity: 0.8;
}

  </style>
</head>
<body>
<form method="post">
<h1>Profile Information</h1>
<?php
  echo("First Name : ");
  echo($row['first_name']);
  echo("<br>");
  echo("Last Name : ");
  echo($row['last_name']);
  echo("<br>");
  echo("Email : ");
  echo($row['email']);
  echo("<br>");
  echo("Headline : ");
  echo($row['headline']);
  echo("<br>");
  echo("Summary : ");
  echo($row['summary']);
  echo("<br><br>");
?>
<button type="submit" class="done" name="done" value="done">Done</button>
</form>
</body>
</html>
