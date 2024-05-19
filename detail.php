<?php
include_once("db/DBConnection.php");

session_start();

$userId = $_SESSION["user_id"];

// Získání dat o hře
$thisGame = htmlspecialchars($_GET["hra"], ENT_QUOTES);

$stmt = $conn->prepare("SELECT `id_hra`, `nazev`, `cena`, `datum_vydani`, `platforma`, `zanr`, `vyrobce`, `informace`, obrazek_detail FROM `hra` WHERE id_hra =:id_hra");
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
switch ($platforma) {
  case "ps5":
    $platformaCela= "PlayStation 5";
    break;
  case "ps4":
    $platformaCela= "PlayStation 4";
    break;
  case "xbox":
    $platformaCela= "Xbox";
    break;
  case "pc":
    $platformaCela= "PC";
    break;
  case "switch":
    $platformaCela= "Nintendo Switch";
    break;
}

$aktualni_datum = (new DateTime())->setTime(0, 0, 0);
$datum_vydani = (new DateTime($datum_vydani))->setTime(0, 0, 0);
$prevedene_datum = $datum_vydani->format('d.m.Y');

$userId = $_SESSION["user_id"];

$stmt4 = $conn->prepare('SELECT `id_uzivatel`, `nick` FROM `uzivatel` WHERE id_uzivatel=:id_uzivatel');
$stmt4->bindParam(":id_uzivatel", $userId);
$stmt4->execute();

$result2 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

foreach($result2 as $row2) {
    $nick = $row2['nick'];
}

$zacatekNicku = substr($nick, 0, 1);

$stmt3 = $conn->prepare("SELECT `id_uzivatel`, `id_hra` FROM `kosik` WHERE id_uzivatel=:id_uzivatel AND id_hra=:id_hra");
$stmt3->bindParam(":id_hra", $thisGame);
$stmt3->bindParam(":id_uzivatel", $userId);
$stmt3->execute();

$result = $stmt3->fetchAll(PDO::FETCH_ASSOC);

if($result) {
    $btn_kosik = "<input type='submit' value='Již v košíku' name='add_cart' disabled >";
} else {
    $btn_kosik =  "<input type='submit' value='Přidat do košíku' name='add_cart'>";
    if  (isset($_POST['add_cart'])) {
      if (isset($_SESSION["user_id"])) {        
        $stmt2 = $conn->prepare("INSERT INTO `kosik`(`id_kosik`, `id_uzivatel`, `id_hra`, `pocet_kopii`) VALUES (NULL, :id_uzivatel, :id_hra, 1)");
        $stmt2->bindParam(":id_hra", $thisGame);
        $stmt2->bindParam(":id_uzivatel", $userId);
        $stmt2->execute();
        header('location:cart.php');
      } else {
        header('location:login.php?login=nologin');
      }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo strtoupper($nameProduct)?></title>
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
            <div class="user">
              <?php
              if (!empty($zacatekNicku)) {
                echo $zacatekNicku;
              }else{
                echo "?";
              } 
              ?>
            </div>
          </a>
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
      <div class="game-discription">
        <h3>Vývojářské studio:<?php echo $vyrobce?><br> Žánr: <?php echo $zanr?><br> Platforma: <?php echo $platformaCela?><br> Datum vydání: <?php echo $prevedene_datum?></h3>
        

      </div>
      
      <div class="price">
        <h2>CENA: <?php echo "\n$priceProduct"?> CZK</h2>
        <form method="post">
          <div class="cart-btn">
            
          <?php
            if($datum_vydani > $aktualni_datum){
              
              echo "Hra vychází: $prevedene_datum";
            }else{
               echo "<i class='fa-solid fa-cart-shopping'></i>";
              echo $btn_kosik;
            }
          ?>

            
          </div>
        </form>
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
