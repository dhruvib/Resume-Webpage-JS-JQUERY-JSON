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

if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline'])
   && isset($_POST['summary'])) {

     // Data validation
     if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
          || strlen($_POST['email']) < 1  || strlen($_POST['headline']) < 1
        || strlen($_POST['summary']) < 1 ) {
         $_SESSION['error'] = 'All fields are required';
         header("Location: add.php");
         return;
     }

     if ( strpos($_POST['email'],'@') === false ) {
         $_SESSION['error'] = 'Email id should contain @';
         header("Location: add.php");
         return;
     }

    $stmt = $pdo->prepare('INSERT INTO Profile
      (user_id, first_name, last_name, email, headline, summary)
      VALUES ( :uid, :fn, :ln, :em, :he, :su)');

    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary'])
    );

    $_SESSION['success'] = "Profile added";
    header("Location: index.php");
    return;
}

if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
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

  .add {
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
<h1>Add A New Profile</h1>
<form method="post">
<p>First Name :
<input type="text" name="first_name" size="40"></p>
<p>Last Name :
<input type="text" name="last_name" size="40"></p>
<p> Email :
<input type="text" name="email" size="40"></p>
<p> Headline :
<input type="text" name="headline" size="40"></p>
<p> Summary :
<input type="text" name="summary" size="40"></p>
<p><input type="submit" class="add" value="Add"/>
<input type="submit" name = "cancel" class="cancel" value="Cancel"/></p>
</form>
</body>
