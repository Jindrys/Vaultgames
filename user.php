<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User page</title>
    <link rel="stylesheet" href="styles.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-top">
        <div class="nav-left">
          <figure class="logo">
            <a href="index.php">
              <img src="./images/logo.svg" alt="logo for vault games" />
            </a>
          </figure>
        </div>
        <div class="nav-right">
          <i class="fa-solid fa-calendar"></i>
          <i class="fa-solid fa-heart"></i>
          <a href="cart.php" class="nav-icon">
            <i class="fa-solid fa-cart-shopping"></i>
          </a>
          <div class="user">J</div>
        </div>        
    </nav>
    
    <main class="user-main">
      <div class="container">
        <div class="user-wrap">
          <div class="main-left">
            <div class="avatar">J</div>
            <div class="user-info">
              <div class="username">
                <h3 class="subtitle">Uživatelské jméno:</h3>
                <span>Jindrys</span>
              </div>
              <div class="email">
                <h3 class="subtitle">Email:</h3>
                <span>nolifer@seznam.cz</span>
              </div>
              <div class="full-name">
                <h3 class="subtitle">Celé jméno:</h3>
                <span>Jindřich Kopejtko</span>
              </div>
            </div>
          </div>
          <div class="main-right">
            <img src="./images/user.svg" alt="fallout boy with thumb" />
          </div>
        </div>
      </div>
    </main>
    <footer>
      <h3>Nenech si ujít novinky</h3>
      <div class="footer-input">
        <i class="fa-solid fa-envelope"></i>
        <input type="email" placeholder="Váš email..." />
        <button>Odebírat</button>
      </div>
      <div class="footer-bottom">
        <span>©2023 VaultGames Inc.</span>
        <span>·</span>
        <span>Všechna práva vyhrazena</span>
      </div>
    </footer>

    <script>
      const bars = document.querySelector(".nav-left i");
      const menu = document.querySelector(".overlay");
      const exit = document.querySelector(".close");

      bars.addEventListener("click", () => {
        menu.classList.add("active");
      });

      exit.addEventListener("click", () => {
        menu.classList.remove("active");
      });
    </script>
  </body>
</html>
