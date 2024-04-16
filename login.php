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
      <div class="inputs">
        <div class="input">
          <label for="email">Email</label>
          <div class="input-wrapper">
            <input id="email" type="email" placeholder="Zadejte svůj email..." />
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
            <input id="password" type="password" placeholder="Zadejte své heslo..."/>
            
          </div>
        </div>
        <div class="login-bottom">
          <div>
            <input class="checkbox" type="checkbox">
            <span>Zůstat přihlášen?</span>
          </div>
          <span class="color">Nedaří se přihlásit?</span>
        </div>
        <a href="user.php">
          <button class="login-btn">Přihlásit se</button>
        </a>
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
