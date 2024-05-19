<?php
include_once("db/DBConnection.php");

session_start();

if (isset($_SESSION["user_id"])) {
    $idUser = $_SESSION["user_id"];
}

if (isset($_POST["odhlasit"])) {
    session_destroy();
    header("location:login.php?logout=success");
}

$stmt = $conn->prepare('SELECT `id_uzivatel`, `nick`, `jmeno`, `prijmeni`, `email`, `telefon`, `ulice_cp`, `mesto`, `psc`, `zeme` FROM `uzivatel` WHERE id_uzivatel=:id_uzivatel');
$stmt->bindParam(":id_uzivatel", $idUser);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $id_uzivatele = $row['id_uzivatel'];
    $nick = $row['nick'];
    $jmeno = $row['jmeno'];
    $prijmeni = $row['prijmeni'];
    $email = $row['email'];
    $telefon = $row['telefon'];
    $mesto = $row['mesto'];
    $ulice_cp = $row['ulice_cp'];
    $psc = $row['psc'];
    $zeme = $row['zeme'];
}

$zacatekNicku = substr($nick, 0, 1);
?>

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
    <nav class="navbar-detail">
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
                <a href="cart.php">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <div class="user"><?php echo $zacatekNicku; ?></div>
            </div>
        </div>
    </nav>
    
    <main class="user-main">
        <div class="container">
            <div class="user-wrap">
                <div class="main-left">
                    <div class="avatar"><?php echo $zacatekNicku; ?></div>
                    <div class="user-info">
                        <div class="username">
                            <h3 class="subtitle">Uživatelské jméno:</h3>
                            <span><?php echo $nick; ?></span>
                        </div>
                        <div class="email">
                            <h3 class="subtitle">Email:</h3>
                            <span><?php echo $email; ?></span>
                        </div>
                        <div class="full-name">
                            <h3 class="subtitle">Celé jméno:</h3>
                            <span><?php echo $jmeno, " ", $prijmeni; ?></span>
                        </div>
                        <div class="full-name">
                            <h3 class="subtitle">Telefon:</h3>
                            <span><?php echo $telefon; ?></span>
                        </div>
                        <div class="full-name">
                            <h3 class="subtitle">Ulice:</h3>
                            <span><?php echo $ulice_cp; ?></span>
                        </div>
                        <div class="full-name">
                            <h3 class="subtitle">PSC:</h3>
                            <span><?php echo $psc; ?></span>
                        </div>
                        <div class="full-name">
                            <h3 class="subtitle">Mesto:</h3>
                            <span><?php echo $mesto; ?></span>
                        </div>
                        <div class="full-name">
                            <h3 class="subtitle">Zeme:</h3>
                            <span><?php echo $zeme; ?></span>
                        </div>
                        <button class="updateButton">Upravit</button>
                        <form method="post">
                            <input type="submit" class="updateButton" value="Odhlásit se" name="odhlasit">
                        </form>
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
</body>
</html>
