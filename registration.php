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
      <?php
      if (isset($_POST["submit"])){
        $nick = $_POST['nick'];
        $jmeno = $_POST["jmeno"];
        $prijmeni = $_POST["prijmeni"];
        $email = $_POST["email"];
        $telefon = $_POST["telefon"];
        $heslo = $_POST["heslo"];
        $hesloHash = password_hash($heslo, PASSWORD_DEFAULT);
        $errors = array();
           if (empty($nick) OR empty($jmeno) OR empty($prijmeni) OR empty($email)OR empty($telefon)OR empty($email)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($heslo) < 8) {
            array_push($errors, "Heslo musí mít alespoň 8 znaků.");
          }
          if (!preg_match('/[A-Z]/', $heslo)) {
            array_push($errors, "Heslo musí obsahovat alespoň jedno velké písmeno.");
          }
          if (!preg_match('/[0-9]/', $heslo)) {
            array_push($errors, "Heslo musí obsahovat alespoň jedno číslo.");
          }
          if (!preg_match('/[\W]/', $heslo)) {
            array_push($errors, "Heslo musí obsahovat alespoň jeden speciální znak.");
          }
          if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert-wrapper'>$error</div>";
            }
            require_once "db/DBConnection.php";//nefunguje mi tam dat db/DBConnection.php
            $sql = "SELECT * FROM uzivatel WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount>0) {
             array_push($errors,"Email already exists!");
            }
            if (count($errors)>0) {
             foreach ($errors as  $error) {
              echo "<div class='alert-wrapper'>$error</div>";
            }
            }else{
             $sql = "INSERT INTO uzivatel (nick, jmeno, prijmeni, email, telefon, heslo) VALUES ( ?, ?, ?, ? ,? ,?) ";
             $stmt = mysqli_stmt_init($conn);
             $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
             if ($prepareStmt) {
                 mysqli_stmt_bind_param($stmt,"sss",$nick, $jmeno, $prijmeni,$email,$telefon, $hesloHash);
                 mysqli_stmt_execute($stmt);
                 echo "<div class='alert-wrapper'>You are registered successfully.</div>";
             }else{
                 die("Something went wrong");
             }
        }
      }
    }?>
      <form action="registration.php" method="post">
        <div class="inputs">
        <div class="input">
            <label for="username">Přezdívka</label>
            <div class="input-wrapper">
                <input name="nick" type="text" placeholder="Zadejte svoji přezdívku..." />
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
        <!-- Jmeno -->
        <div class="name-wrapper">
            <div class="input">
                <label for="name">Jméno</label>
                <div class="input-wrapper">
                    <input name="jmeno" type="text" placeholder="Zadejte jméno..." />
                </div>
            </div>
            <div class="input">
                <label for="surname">Příjmení</label>
                <div class="input-wrapper">
                    <input name="prijmeni" type="text" placeholder="Zadejte příjmení..." />
                </div>
            </div>
        </div>
        <!-- Email -->
        <div class="input">
            <label for="email">Email</label>
            <div class="input-wrapper">
              <input name="email" type="email" placeholder="Zadejte svůj email..." />
              <i class="fa-solid fa-envelope"></i>
            </div>
        </div>
        <!-- Telefon -->
        <div class="input">
            <label for="phone">Telefon</label>
            <div class="input-wrapper">
              <input name="telefon" type="tel" placeholder="Zadejte své telefoní číslo..." />
              <i class="fa-solid fa-phone"></i>
            </div>
        </div>
        <!-- Heslo -->
        <div class="input">
          <label for="password">Heslo</label>
          <div class="input-wrapper">
            <div class="password-icon">
              <i class="fa-solid fa-lock"></i>
            </div>
            <div class="eye-icon">
              <i class="fa-solid fa-eye-slash"></i>
            </div>
            <input name="heslo" type="password" placeholder="Zadejte heslo..."/>
          </div>
        </div>
        <div class="login-bottom">
        </div>
          <input type="submit" class="login-btn"  value="Registrovat se" name="submit"/>
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