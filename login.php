
<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','dhruvi','dhruvi');
session_start();

unset($_SESSION['name']);
unset($_SESSION['user_id']);

if(isset($_POST['cancel'])){
  header("Location: index.php");
  return;
}

if ( isset($_POST['email']) && isset($_POST['psw'])  ) {
    try{
      $salt = 'XyZzy12*_';
      $check = hash('md5', $salt.$_POST['psw']);

      $stmt = $pdo->prepare('SELECT name, user_id FROM users
         WHERE email = :em
         AND password = :pw');

      $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ( $row === FALSE ) {
         $_SESSION['error'] = "Login incorrect.";
      } else {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
      }
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
}
?>

<html>
<head>
  <title>Dhruvi Bhagat's Login Page</title>
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

button {
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
  padding: 10px 18px;
  background-color: #6495ed;
}

button:hover {
  opacity: 0.8;
}
  </style>
</head>
<body>
<h1>Please Login</h1>
<?php
  if(isset($_SESSION['error'])){
    echo ('<p style="color:red;">'.htmlentities($_SESSION['error'])."</p>");
    unset($_SESSION['error']);
  }

  $oldemail = isset($_POST['email']) ? $_POST['email'] : '';

  $oldpass = isset($_POST['psw']) ? $_POST['psw'] : '';
?>
<form action="login.php" method="POST">
<div class="container">
  <label for="email"><b>Email</b></label><br>
  <input type="text" placeholder="Enter Email" name="email" id="email" value="<?= htmlentities($oldemail)?>">
  <br><br>
  <label for="psw"><b>Password</b></label><br>
  <input type="password" placeholder="Enter Password" name="psw" id="psw" value="<?= htmlentities($oldpass)?>">
  <br><br>
  <button type="submit" onclick="return doValidate();" value="Log in">Login</button>
  <button type="submit" name="cancel" class="cancel" value="cancel">Cancel</button>
</div>
</form>
<script>
function doValidate(){
  console.log("Inside doValidate function");
  try{
    em = document.getElementById('email').value;
    pass = document.getElementById('psw').value;
    console.log("email is "+em+ " password is "+pass);
    if(em == null || em == "" || pass == null || pass == ""){
      alert("Both the fields must be filled");
      return false;
    }
    if(em.indexOf("@")== -1){
      alert("Invalid email address");
      return false;
    }
    return true;
  }
  catch(e){
    return false;
  }
}
</script>
</body>
</html>
