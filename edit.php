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

if(isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline'])
   && isset($_POST['summary'])){

     if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
          || strlen($_POST['email']) < 1  || strlen($_POST['headline']) < 1
        || strlen($_POST['summary']) < 1 ) {
         $_SESSION['error'] = 'All fields are required';
         header("Location: edit.php?profile_id=".$_POST['profile_id']);
         return;
     }

     if ( strpos($_POST['email'],'@') === false ) {
         $_SESSION['error'] = 'Email id should contain @';
         header("Location: edit.php?profile_id=".$_POST['profile_id']);
         return;
     }

     $data = [
    'fn' => $_POST['first_name'],
    'ln' => $_POST['last_name'],
    'em' => $_POST['email'],
    'hd' => $_POST['headline'],
    'sm' => $_POST['summary'],
    'pi' => $_POST['profile_id'],
    ];

    $sql = "UPDATE profile SET first_name=:fn, last_name=:ln, email=:em,
    headline=:hd, summary=:sm WHERE profile_id=:pi";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);

    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;

}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}


$profile_id = htmlentities($row['profile_id']);
$f = htmlentities($row['first_name']);
$l = htmlentities($row['last_name']);
$e = htmlentities($row['email']);
$h = htmlentities($row['headline']);
$s = htmlentities($row['summary']);

if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

?>

<html>
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

  .save{
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
</head>
<body>
<h1>Edit Profile</h1>
<form method="post">
<p>First Name :
<input type="text" name="first_name" size="40" value = "<?= $f ?>"></p>
<p>Last Name :
<input type="text" name="last_name" size="40" value = "<?= $l ?>"></p>
<p> Email :
<input type="text" name="email" size="40" value = "<?= $e ?>"></p>
<p> Headline :
<input type="text" name="headline" size="40" value = "<?= $h ?>"></p>
<p> Summary :
<input type="text" name="summary" size="40" value = "<?= $s ?>"></p>
<input type="hidden" name = profile_id value= "<?= $profile_id ?>"/>
<p><input type="submit" class = "save" value="Save"/>
<input type="submit" name = "cancel" class="cancel" value="Cancel"/></p>
</form>
</body>
