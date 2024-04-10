<?php
  include_once ("db/DBConnection.php");
  $thisGame = htmlspecialchars($_GET["hra"], ENT_QUOTES);
  $stmt = $conn->prepare("SELECT `id_hra`, `nazev`, `cena`, `datum_vydani`, `platforma`, `zanr`, `výrobce`, `informace`, obrazek_detail FROM `hra` WHERE id_hra =:id_hra");
  $stmt->bindParam(":id_hra", $thisGame);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($result as $row) { 
    $gameId = $row['id_hra'];
    $imageProduct = $row['obrazek_detail'];
    $nameProduct = $row['nazev'];
    $priceProduct = $row['cena'];
    $datum_vydani = $row['datum_vydani'];
    $platforma = $row['platforma'];
    $zanr = $row['zanr'];
    $vyrobce = $row['vyrobce'];
    $info = $row['informace'];
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FC24</title>
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
    <!-- Navbar -->
    <nav class="navbar-detail">
      <div class="nav-top">
        <div class="nav-left">
          <i class="fa-solid fa-bars"></i>
          <a href="index.php">
            <figure class="logo">
            <img src="./images/logo.svg" alt="logo for vault games" />
          </figure>
          </a>
        </div>
        <div class="nav-right">
          <i class="fa-solid fa-calendar"></i>
          <i class="fa-solid fa-heart"></i>
          <a href="cart.php">
              <i class="fa-solid fa-cart-shopping"></i>
          </a>
          <a href="login.php">
            <div class="user">J</div>
          </a>
        </div>
        <div class="menu">
          <div class="overlay">
            <ul class="menu-list">
              <li>PlayStation</li>
              <li>Xbox</li>
              <li>Nintendo</li>
              <li>PC</li>
              <li>Bazar</li>
              <li>Přislušenství</li>
            </ul>
            <div class="close">X</div>
          </div>
        </div>
      </div>
    </nav>
  <main>
    <section class="detail">
      <div class="game">
        <img class="game" src="<?php echo $imageProduct?>"alt='fifa 24'> 
      </div>
    <div class="detail-wrapper">
      <div class="game-name">
        <h1><?php echo $nameProduct?></h1>
      </div>
      <div class="game-discription">
        <p><?php echo $info ?></p>
      </div>
      
      <div class="price">
        <h2>CENA: <?php echo $priceProduct?> CZK</h2>
        <div class="cart-btn">
          <i class="fa-solid fa-cart-shopping"></i>
          <button>Přidat do košíku</button>
        </div>
        <div class="cart-btn_inv">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
      </div>
    </div>
    </section>
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
  </body>
</html>