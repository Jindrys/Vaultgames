<?php
include_once ("db/DBConnection.php");

  $error_user_name = "";
  $error_user_firstname ="";
  $error_user_lastname ="";
  $error_user_password = "";
  $error_user_email = "";

  $user_name = ""; 
  $user_firstname = "";
  $user_lastname = "";
  $user_email = "";
  $user_password = "";

  $used_email = "";
  $used_username = "";




 if (isset($_POST["send"])) {

  if (!empty($_POST["user_name"])) {
   $regexp = "/^[A-Za-z0-9AEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
    if (preg_match($regexp, $_POST["user_name"])) {
        $user_name = trim($_POST["user_name"]);
    } else {  
      $error_user_name = '<label class="alert-wrapper">Pouze písmena a číslice</label>'; 
    }   
  } else {
    $error_user_name = '<label class="alert-wrapper">Povinné</label>';
  }

  if (!empty($_POST["user_firstname"])) {
    $regexp = "/^[A-Za-zAEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
    if (preg_match($regexp, $_POST["user_firstname"])) {
        $user_firstname = trim($_POST["user_firstname"]);
    } else {  
      $error_user_firstname = '<label class="alert-wrapper">Zadejte opravdové jméno</label>';
    }
  } else {
    $error_user_firstname = '<label class="alert-wrapper">Povinné</label>';
  }

  if (!empty($_POST["user_lastname"])) {
    $regexp = "/^[A-Za-zAEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
    if (preg_match($regexp, $_POST["user_lastname"])) {
        $user_lastname = trim($_POST["user_lastname"]);
    } else {  
      $error_user_lastname = '<label class="alert-wrapper">Zadejte opravdové příjmení</label>';
    }
  } else {
    $error_user_lastname = '<label class="alert-wrapper">Povinné</label>';
  }
  
  if (!empty($_POST["user_email"])) {
    $user_email = filter_var($_POST["user_email"], FILTER_SANITIZE_EMAIL);
    
    if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $user_email = trim($_POST["user_email"]);
        $stmt2 = $conn->prepare("SELECT email FROM `uzivatel` WHERE email=:email");
        $stmt2->bindParam(':email', $user_email);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        if ($result2) {
            $error_user_email = '<label class="alert-wrapper">Tato emailová adresa je již použita</label>';
        } else {
          $user_email = trim($_POST["user_email"]);
        }
    } else {
        $error_user_email = '<label class="alert-wrapper">Neplatná e-mailová adresa</label>';
    }
} else {
    $error_user_email = '<label class="alert-wrapper">E-mailová adresa je povinná</label>';
}


  if (!empty($_POST["user_password"])) {
    $user_password = $_POST["user_password"];
        $regexp = "/^[A-Za-z0-9\-\_\.]{8,}$/";
      if (preg_match($regexp, $_POST["user_password"])) {
          $user_password = trim($_POST["user_password"]);
          $user_password = password_hash($user_password, PASSWORD_DEFAULT);
      } else {  
        $error_user_password = '<label class="alert-wrapper">Pouze písmena, číslice a zanky:_ . - </label>';
      }
  } else {
    $error_user_password = '<label class="alert-wrapper">Povinné</label>';
  }
if ($error_user_name == '' && $error_user_firstname == "" && $error_user_lastname == "" && $error_user_email == "" && $error_user_password == "" ) {
  $stmt = $conn->prepare("INSERT INTO `uzivatel`(id_uzivatel, `nick`, `jmeno`, `prijmeni`, `email`, `heslo`, `role`) VALUES (NULL, :nick, :jmeno, :prijmeni, :email, :heslo, 0)");
  $stmt->bindParam(":nick", $user_name);
  $stmt->bindParam(":jmeno", $user_firstname);
  $stmt->bindParam(":prijmeni", $user_lastname);
  $stmt->bindParam(":email", $user_email);
  $stmt->bindParam(":heslo", $user_password);
  $stmt->execute();
}
}

    ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrace</title>
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
        <h2 class="login-headline">VÍTÁME NOVÉHO <br>GAMERA!</h2>
      <!-- Nick -->
      <form method="post">
        <div class="inputs">
        <div class="input">
          <?php echo $error_user_name?>
            <label for="username">Přezdívka</label>
            <div class="input-wrapper">
                <input name="user_name" type="text" placeholder="Zadejte svoji přezdívku..." />
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
        <!-- Jmeno -->
        <div class="name-wrapper">
            <div class="input">
            <?php echo $error_user_firstname?>
                <label for="name">Jméno</label>
                <div class="input-wrapper">
                    <input name="user_firstname" type="text" placeholder="Zadejte jméno..." />
                </div>
            </div>
            <div class="input">
            <?php echo $error_user_lastname?>
                <label for="surname">Příjmení</label>
                <div class="input-wrapper">
                    <input name="user_lastname" type="text" placeholder="Zadejte příjmení..." />
                </div>
            </div>
        </div>
        <!-- Email -->
        <div class="input">
        <?php echo $error_user_email?>
            <label for="email">Email</label>
            <div class="input-wrapper">
              <input name="user_email" type="email" placeholder="Zadejte svůj email..." />
              <i class="fa-solid fa-envelope"></i>
            </div>
        </div>
        <!-- Heslo -->
        <div class="input">
        <?php echo $error_user_password?>
          <label for="password">Heslo</label>
          <div class="input-wrapper">
            <div class="password-icon">
              <i class="fa-solid fa-lock"></i>
            </div>
            <div class="eye-icon">
              <i class="fa-solid fa-eye-slash"></i>
            </div>
            <input name="user_password" type="password" placeholder="Zadejte heslo..."/>
          </div>
        </div>
        <div class="login-bottom">
        </div>
          <input type="submit" class="login-btn"  value="Registrovat se" name="send"/>
        </form>
        <div>
          <p>Už máš účet? <a href=login.php><span class="color">Přihlaš se</span></a></p>
        </div>
      </div>
      </div>   
     </div>
    </section>
  </body>
  </main>
</html>