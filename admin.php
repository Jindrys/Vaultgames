<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrace</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">
</head>
<body>
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
        <a href="login.php">
          <div class="user">J</div>
        </a>
      </div>
    </div>
  </nav>
  <div class="addGameContainer">
    <h2 class="addTitle">Přidat hru</h2>
    <div class="addInputs">
      <input class="addInput" placeholder="Název hry" />
      <input class="addInput" placeholder="Cena v Kč" />
      <input class="addInput" placeholder="Datum vydání" value="2024-04-30"/>
      <input class="addInput" placeholder="Platforma" />
      <input class="addInput" placeholder="Žánr" />
      <input class="addInput" placeholder="Výrobce" />
      <input class="addInput" placeholder="Informace o hře" />
      <input class="addInput" placeholder="Obrazek - malý" />
      <input class="addInput" placeholder="Obrazek - velký" />
    </div>
    <button class="addConfirmButton">Uložit do databáze</button>
  </div>
  <div class="addGameContainer">
    <h2 class="addTitle">Upravit hru</h2>
    <div class="addInputs">
      <input class="addInput" placeholder="Název hry" />
      <button class="findButton">Najít hru</button>
      <input class="addInput" placeholder="Cena v Kč" />
      <input type="date" class="addInput" placeholder="Datum vydání (rrrr-mm-dd)" />
      <input class="addInput" placeholder="Platforma" />
      <input class="addInput" placeholder="Žánr" />
      <input class="addInput" placeholder="Výrobce" />
      <input class="addInput" placeholder="Informace o hře" />
      <input class="addInput" placeholder="Obrazek - malý" />
      <input class="addInput" placeholder="Obrazek - velký" />
    </div>
    <button class="addConfirmButton">Uložit do databáze</button>
  </div>
</body>
</html>
