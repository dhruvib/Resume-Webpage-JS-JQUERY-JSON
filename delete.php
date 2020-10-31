<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','dhruvi','dhruvi');
session_start();

if( !isset($_SESSION['user_id'])){
  die("ACCESS DENIED");
  return;
}

if(isset($_POST['cancel'])){
  header("Location: index.php");
  return;
}

if ( isset($_POST['delete']) && $_POST['profile_id']) {
    $sql = "DELETE FROM profile WHERE profile_id = :pi";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':pi' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name,profile_id FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<html>
<head>
  <style>
  body {font-family: Arial, Helvetica, sans-serif;}
  form {border: 3px solid #f1f1f1;}

  input[type=text], input[type=password] {
  width: 40%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.delete {
  background-color: #4CAF50;
  color: white;
  padding: 10px 18px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 20%;
}

.cancel{
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
<head>
<body>
<form method="post">
<h1>Deleting Profile</h1>
<?php
  echo("First Name : ");
  echo($row['first_name']);
  echo("<br>");
  echo("Last Name : ");
  echo($row['last_name']);
  echo("<br><br>");
?>
<input type="hidden" name = profile_id value= "<?=$row['profile_id'] ?>"/>
<button type="submit" name="delete" class="delete" value="delete">Delete</button>
<button type="submit" name="cancel" class="cancel" value="cancel">Cancel</button>
</form>
</body>
