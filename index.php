<?php
include_once("db/DBConnection.php");

session_start();

$userId = $_SESSION["user_id"];

$stmt4 = $conn->prepare('SELECT `id_uzivatel`, `nick` FROM `uzivatel` WHERE id_uzivatel=:id_uzivatel');
$stmt4->bindParam(":id_uzivatel", $userId);
$stmt4->execute();

$result3 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

foreach($result3 as $row3) {
    $nick = $row3['nick'];
}

$zacatekNicku = substr($nick, 0, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1 " />
    <title>VaultGames</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="nav-top">
        <div class="nav-left">
            <i class="fa-solid fa-bars"></i>
            <figure class="logo">
                <img src="./images/logo.svg" alt="logo for vault games" />
            </figure>
        </div>
        <div class="nav-right">
            <i class="fa-solid fa-calendar"></i>
            <i class="fa-solid fa-heart"></i>
            <a href="cart.php" class="nav-icon">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
            <a href="login.php">
                <div class="user">
                <?php
                if (!empty($zacatekNicku)) {
                    echo $zacatekNicku;
                } else {
                    echo "?";
                }
                ?>
                </div>
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
    <div class="line"></div>
    <div class="nav-bot">
        <ul class="filters">
            <li>PlayStation</li>
            <li>Xbox</li>
            <li>Nintendo</li>
            <li>PC</li>
            <li>Bazar</li>
            <li>Přislušenství</li>
        </ul>
    </div>
</nav>
<main>
    <!-- Sekce hero -->
    <section class="hero">
        <main class="hero-content">
            <h1>Vítejte ve VaultGames</h1>
            <p>
                Jsme domovem pro vaše nejžhavější herní příběhy!<br>Prozkoumejte naši
                skvělou sbírku videoher.
            </p>
            <div class="search">
                <div class="search-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="search" placeholder="Vyhledejte svou hru..." />
            </div>
        </main>
    </section>
    <!-- Sekce novinky -->
    <section class="news">
        <header>
            <h2 class="title">Novinky</h2>
            <span>Zobrazit vše</span>
        </header>
        <div class="news-content">
        <?php
        $stmt = $conn->prepare("SELECT `id_hra`, `nazev`, `cena`, `datum_vydani`, `obrazek` FROM `hra` WHERE `datum_vydani`<CURRENT_DATE ORDER BY `datum_vydani` DESC LIMIT 10");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $row) {
            $gameId = $row['id_hra'];
            $imageProduct = $row['obrazek'];
            $nameProduct = $row['nazev'];
            $priceProduct = $row['cena'];

            echo "<div class='news-card'>
                <a href='detail.php?hra=$gameId'>
                  <div class='news-card_img'>
                    <img src='$imageProduct' alt='game_image' />
                  </div>
                </a>
              <div class='news-card_content'>
                <div class='news-card_top'>
                  <h3>$nameProduct</h3>
                  <div class='favorite'><i class='fa-regular fa-heart'></i></div>
                </div>
                <div class='news-card_bot'>
                  <strong><span>$priceProduct CZK</span></strong>
                </div>
              </div>
            </div>";
        }
        ?>
        </div>
    </section>
    <!-- Sekce soon -->
    <section class="news">
        <header>
            <h2 class="title">Budou vycházet</h2>
            <span>Zobrazit vše</span>
        </header>
        <div class="news-content">
        <?php
        $stmt2 = $conn->prepare("SELECT `id_hra`, `nazev`, `cena`, `datum_vydani`, `obrazek` FROM `hra` WHERE `datum_vydani`>CURRENT_DATE ORDER BY `datum_vydani` ASC LIMIT 10");
        $stmt2->execute();

        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach($result2 as $row2) {
            $gameId = $row2['id_hra'];
            $imageProduct = $row2['obrazek'];
            $nameProduct = $row2['nazev'];
            $priceProduct = $row2['cena'];
            $datum_vydani = $row2['datum_vydani'];

            echo "<div class='news-card'>
                <a href='detail.php?hra=$gameId'>
                  <div class='news-card_img'>
                    <img src='$imageProduct' alt='game_image' />
                  </div>
                </a>
              <div class='news-card_content'>
                <div class='news-card_top'>
                  <h3>$nameProduct</h3>
                  <div class='favorite'><i class='fa-regular fa-heart'></i></div>
                </div>
                <div class='news-card_bot'>
                  <strong><span>$priceProduct CZK</span></strong>
                </div>
                <div class='shop'>
                  Datum vydani <br>$datum_vydani
                </div>
              </div>
            </div>";
        }
        ?>
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
<script>
    // Navbar
    const bars = document.querySelector(".nav-left i");
    const menu = document.querySelector(".overlay");
    const exit = document.querySelector(".close");

    bars.addEventListener("click", () => {
        menu.classList.add("active");
    });

    exit.addEventListener("click", () => {
        menu.classList.remove("active");
    });

    // Přidat do oblíbených
    const favorites = document.querySelectorAll(".favorite");

    for (let i = 0; i < favorites.length; i++) {
        favorites[i].addEventListener("click", function () {
            const favoriteStar = this.querySelectorAll(".favorite i")[0];

            if (favoriteStar.classList.contains("fa-regular")) {
                favoriteStar.classList.remove("fa-regular");
                favoriteStar.classList.add("fa-solid");
            } else {
                favoriteStar.classList.remove("fa-solid");
                favoriteStar.classList.add("fa-regular");
            }
        });
    }
</script>
</body>
</html>
