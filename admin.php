<?php
  include_once ("db/DBConnection.php");
  session_start();
  /*if (!isset($_SESSION["role"])) {
    header('location:index.php');
  }
  if($_SESSION["role"] == 1) {
    header('location:user_account.php');
  }
  */

  $nazev_hry = "";
  $cena_hry = "";
  $datum_vydani = "";
  $platforma = "";
  $zanr = "";
  $vyrobce = "";
  $informace = "";
  $obr_maly_cesta = "";
  $obr_velky_cesta = "";

  $err_nazev = "";
  $err_cena = "";
  $err_datum = "";
  $err_zanr = "";
  $err_vyrobce = "";
  $err_informace = "";
  $err_obr_maly = "";
  $err_obr_velky = "";

  
  



  if (isset($_POST["add"])) {

    $vse_ok = 0;

    if (!empty($_POST["nazev_add"])) {
      $regexp = "/^[A-Za-z0-9AEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
      if (preg_match($regexp, $_POST["nazev_add"])) {
        $nazev_hry = trim($_POST["nazev_add"]);
      } else {  
        $err_nazev = '<label class="alert-admin">Pouze písmena a číslice</label>'; 
      }
    } else {
      $err_nazev = '<label class="alert-admin">Musí být vyplněno</label>';
    }

    if (!empty($_POST["cena_add"])) {
      $regexp = "/^[0-9]+$/";
      if (preg_match($regexp, $_POST["cena_add"])) {
        $cena_hry = trim($_POST["cena_add"]);
      } else {  
        $err_cena = '<label class="alert-admin">Pouze číslice</label>'; 
      }
    } else {
      $err_cena = '<label class="alert-admin">Musí být vyplněno</label>';
    }

    if (!empty($_POST["datum_add"])) {
      $datum_vydani = $_POST["datum_add"];
      $datum_input = $_POST["datum_add"];
      $datum_objekt = DateTime::createFromFormat('d.m.Y', $datum_vydani);
      
      if ($datum_objekt === false) {
        $err_datum = '<label class="alert-admin">Neplatný formát data. Použijte formát DD.MM.RRRR.</label>'; 
      } else {
        $prepsane_datum = $datum_objekt->format('Y-m-d');
      }  
    } else {
      $err_datum = '<label class="alert-admin">Vyberte datum</label>';
    }

    $platforma = $_POST["platforma_add"];

    if (!empty($_POST["zanr_add"])) {
      $regexp = "/^[A-Za-z0-9AEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
      if (preg_match($regexp, $_POST["zanr_add"])) {
        $zanr = trim($_POST["zanr_add"]);
      } else {  
        $err_zanr = '<label class="alert-admin">Pouze písmena a čísla</label>'; 
      }
    } else {
      $err_zanr = '<label class="alert-admin">Musí být vyplněno</label>';
    }

    if (!empty($_POST["vyrobce_add"])) {
      $regexp = "/^[A-Za-zAEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
      if (preg_match($regexp, $_POST["vyrobce_add"])) {
        $vyrobce = trim($_POST["vyrobce_add"]);
      } else {  
        $err_vyrobce = '<label class="alert-admin">Pouze písmena</label>'; 
      }
    } else {
      $err_vyrobce = '<label class="alert-admin">Musí být vyplněno</label>';
    }

    if (!empty($_POST["informace_add"])) {
      $regexp = "/^[A-Za-z0-9AEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
      if (preg_match($regexp, $_POST["informace_add"])) {
        $informace = trim($_POST["informace_add"]);
      } else {  
        $err_informace = '<label class="alert-admin">Pouze písmena a čísla</label>'; 
      }
    } else {
      $err_informace = '<label class="alert-admin">Musí být vyplněno</label>';
    }

    // ulozeni obrazku

    if ($err_nazev == "" && $err_cena == "" && $err_datum == "" && $err_zanr == "" && $err_vyrobce == "" && $err_informace == "") {
      $stmt = $conn->prepare("INSERT INTO `hra`(`id_hra`, `nazev`, `cena`, `datum_vydani`, `platforma`, `zanr`, `výrobce`, `informace`, `obrazek`, `obrazek_detail`) VALUES (NULL, :nazev, :cena, :datum, :platforma, :zanr, :vyrobce, :informace, :cesta_obr_maly, :cesta_obr_velky)");
      $stmt->bindParam(":nazev", $nazev_hry);
      $stmt->bindParam(":cena", $cena_hry);
      $stmt->bindParam(":datum", $datum_vydani);
      $stmt->bindParam(":platforma", $platforma);
      $stmt->bindParam(":zanr", $zanr);
      $stmt->bindParam(":vyrobce", $vyrobce);
      $stmt->bindParam(":informace", $informace);
      $stmt->bindParam(":cesta_obr_maly", $obr_maly_cesta);
      $stmt->bindParam(":cesta_obr_velky", $obr_velky_cesta);
      $stmt->execute();

      $vse_ok = 1;
    }


  }
    
  print_r ($_POST);
?>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1 "/>
    <title>Admin</title>
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
  <!-- Přidat hru --> 
  <div class="addGameContainer">
    <h2 class="add_title">Přidat hru</h2>
    <form method="post">
      <div class="addInputs">
      <?php echo $err_nazev;?>
      <input name="nazev_add" class="addInput" placeholder="Název hry" value="<?php echo htmlspecialchars($nazev_hry);?>"/>
      <?php echo $err_cena;?>
      <input name="cena_add" class="addInput" placeholder="Cena v Kč" value="<?php echo htmlspecialchars($cena_hry);?>"/>
      <?php echo $err_datum;?>
      <input name="datum_add" type="date" class="addInput" placeholder="Datum vydání" value="<?php echo htmlspecialchars($datum_input);?>"/>
      <select name="platforma_add" class="addInput">
        <option value="ps5" <?php if ($platforma == 'ps5') echo 'selected="selected"'; ?>>Playstation5</option>
        <option value="ps4" <?php if ($platforma == 'ps4') echo 'selected="selected"'; ?>>Playstation4</option>
        <option value="xbox" <?php if ($platforma == 'xbox') echo 'selected="selected"'; ?>>Xbox</option>
        <option value="pc" <?php if ($platforma == 'pc') echo 'selected="selected"'; ?>>Počítač</option>
        <option value="switch" <?php if ($platforma == 'switch') echo 'selected="selected"'; ?>>Nintento Switch</option>
      </select>
      <?php echo $err_zanr;?>
      <input name="zanr_add" class="addInput" placeholder="Žánr" value="<?php echo htmlspecialchars($zanr);?>"/>
      <?php echo $err_vyrobce;?>
      <input name="vyrobce_add" class="addInput" placeholder="Výrobce" value="<?php echo htmlspecialchars($vyrobce);?>"/>
      <?php echo $err_informace;?>
      <input name="informace_add" class="addInput" placeholder="Informace o hře" value="<?php echo htmlspecialchars($informace);?>"/>
      
      <input name="obr_maly_add" type="file" class="addInput" placeholder="Obrazek - malý" />
      
      <input name="obr_velky_add" type="file" class="addInput" placeholder="Obrazek - velký" />
    </div>
    <input type="submit" name="add" class="addConfirmButton" value="Nahrát do databáze"/>
    </form>
    
  </div>
  <!-- Upravit hru -->
  <div class="addGameContainer">
    <h2 class="add_title">Upravit hru</h2>
    <div class="addInputs">
      <input class="addInput" placeholder="Název hry" />
      <button class="findButton">Najít hru</button>
      <input class="addInput" placeholder="Cena v Kč" />
      <input type="date" class="addInput" placeholder="Datum vydání" />
      <select class="addInput">
        <option value="ps5">Playstation5</option>
        <option value="ps4">Playstation4</option>
        <option value="xbox">Xbox</option>
        <option value="pc">Počítač</option>
        <option value="switch">Nintento Switch</option>
      </select>
      <input class="addInput" placeholder="Žánr" />
      <input class="addInput" placeholder="Výrobce" />
      <input class="addInput" placeholder="Informace o hře" />
      <input type="file" class="addInput" placeholder="Obrazek - malý" />
      <input type="file" class="addInput" placeholder="Obrazek - velký" />
    </div>
    <button class="addConfirmButton">Uložit do databáze</button>
  </div>
</body>
</html>
