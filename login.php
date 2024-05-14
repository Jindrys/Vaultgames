<?php
    include_once("db/DBConnection.php");

    session_start();

    if(isset($_SESSION["user_id"])) {
      header("location:user.php");
     }

     if(isset($_GET['login']) && $_GET['login'] === 'nologin') {
      echo '<script>alert("Musíte se přihlásit");</script>';
     }
    if (isset ($_POST["login"])) {

      $user_email = $_POST['user_email'];
      $user_password = $_POST['user_password'];
      $error_email = "";
      $error_password = "";

      $stmt = $conn->prepare("SELECT * FROM `uzivatel` WHERE email=:email");
      $stmt->bindParam(":email", $user_email);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$result) {
          $error_email = '<label class="text-danger">Neplatný nebo neověřený e-mail</label>';
      } else {
          if (password_verify($user_password, $result['heslo']) && ($result["role"] == NULL || $result["role"] == 0)) {
              $_SESSION['user_id'] = $result['id_uzivatel'];
              $_SESSION['authority'] = $result['role'];
              header('location:user.php?login=success');
              echo "funguje to";
          } else if(password_verify($user_password, $result['heslo']) && $result["role"] == 1) {
              $_SESSION['user_id'] = $result['id_uzivatel'];
              $_SESSION['authority'] = $result['role'];
              header('location:admin.php?login=success');
             
          } else {
              $error_password = '<label class="text-danger">Nesprávné heslo</label>';
          }
       }
    }

    
    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">
  </head>
<main>
  <body>
    <section class="login">
      <div class="login-wrapper">
        <div>
          <a href="index.php">
            <figure class="login-logo">
              <img src="./images/login-logo.svg" alt="logo-voultgames">
            </figure>
          </a>
        <h2 class="login-headline">VÍTEJ ZPÁTKY <br>GAMERE!</h2>
      <form method="post">
      <div class="inputs">
        <div class="input">
          <label for="email">Email</label>
          <div class="input-wrapper">
            <input type="email" placeholder="Zadejte svůj email..." name="user_email"/>
            <i class="fa-solid fa-envelope"></i>
          </div>
        </div>
        <div class="input">
          <label for="password">Heslo</label>
          <div class="input-wrapper">
            <div class="password-icon">
              <i class="fa-solid fa-lock"></i>
            </div>
            <div class="eye-icon">
              <i class="fa-solid fa-eye-slash"></i>
            </div>
            <input type="password" placeholder="Zadejte své heslo..." name="user_password"/>
            
          </div>
        </div>
        <div class="login-bottom">
          <div>
            <input class="checkbox" type="checkbox">
            <span>Zůstat přihlášen?</span>
          </div>
          <span class="color">Nedaří se přihlásit?</span>
        </div>
        <input type="submit" class="login-btn"  value="Přihlásit se" name="login"/>
      </form>  
        <div>
          <p>Nejsi zaregistrovaný? <a href="registration.php"><span class="color">Zaregistruj se</span></a></p>
        </div>
      </div>
      </div>   
     </div>
      
    </section>
  </body>
  </main>
</html>
