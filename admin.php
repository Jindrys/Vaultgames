<?php
include_once("db/DBConnection.php");
session_start();

if (!isset($_SESSION["role"])) {
    header('location:index.php');
}

if ($_SESSION["role"] == 0) {
    header('location:user.php');
}

$nazev_hry = "";
$cena_hry = "";
$datum_vydani = "";
$platforma = "";
$zanr = "";
$vyrobce = "";
$informace = "";
$obr_maly_cesta = "";
$obr_velky_cesta = "";
$add_uspech = "";
$delete_uspech = "";

$err_nazev = "";
$err_cena = "";
$err_datum = "";
$err_zanr = "";
$err_vyrobce = "";
$err_informace = "";
$err_obr_maly = "";
$err_obr_velky = "";
$err_soubor = "";
$err_soubor2 = "";

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

    if ($err_nazev == "" && $err_cena == "" && $err_datum == "" && $err_zanr == "" && $err_vyrobce == "" && $err_informace == "") {
        // ulozeni obrazku maly
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $err_soubor = '<label class="alert-admin">Povinné</label>';

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $err_soubor = "<label class='alert-admin'>File is an image - " . $check["mime"] . ".</label>";
            $uploadOk = 1;
        } else {
            $err_soubor = "<label class='alert-admin'>Toto není obrázek</label>";
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            $err_soubor = "<label class='alert-admin'>Obrázek je již v databázi</label>";
            $uploadOk = 0;
        }

        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $err_soubor = "<label class='alert-admin'>Obrázek je moc velký</label>";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $err_soubor = "<label class='alert-admin'>Soubor může být pouze  jpg, png, jpeg nebo gif</label>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $err_soubor = "<label class='alert-admin'>Soubor neuložen</label>";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $err_soubor = "";
            } else {
                $err_soubor = "<label class='alert-admin'>Ukládání selhalo</label>";
            }
        }

        // uložení obrazku velky
        $target_dir2 = "images_detail/";
        $target_file2 = $target_dir2 . basename($_FILES["fileToUpload2"]["name"]);
        $uploadOk2 = 1;
        $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
        $err_soubor2 = '<label class="alert-admin">Povinné</label>';

        $check2 = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
        if ($check2 !== false) {
            $err_soubor2 = "<label class='alert-admin'>File is an image - " . $check2["mime"] . ".</label>";
            $uploadOk2 = 1;
        } else {
            $err_soubor2 = "<label class='alert-admin'>Toto není obrázek</label>";
            $uploadOk2 = 0;
        }

        if (file_exists($target_file2)) {
            $err_soubor2 = "<label class='alert-admin'>Obrázek je již v databázi</label>";
            $uploadOk2 = 0;
        }

        if ($_FILES["fileToUpload2"]["size"] > 5000000) {
            $err_soubor2 = "<label class='alert-admin'>Obrázek je moc velký</label>";
            $uploadOk2 = 0;
        }

        if ($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg" && $imageFileType2 != "gif") {
            $err_soubor2 = "<label class='alert-admin'>Soubor může být pouze  jpg, png, jpeg nebo gif</label>";
            $uploadOk2 = 0;
        }

        if ($uploadOk2 == 0) {
            $err_soubor2 = "<label class='alert-admin'>Soubor neuložen</label>";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file2)) {
                $err_soubor2 = '';
            } else {
                $err_soubor2 = "<label class='alert-admin        '>Ukládání selhalo</label>";
              }
          }
      }
  
      if ($err_nazev == "" && $err_cena == "" && $err_datum == "" && $err_zanr == "" && $err_vyrobce == "" && $err_informace == "" && $err_soubor == "" && $err_soubor2 == "") {
          $stmt = $conn->prepare("INSERT INTO `hra`(`id_hra`, `nazev`, `cena`, `datum_vydani`, `platforma`, `zanr`, `vyrobce`, `informace`, `obrazek`, `obrazek_detail`) VALUES (NULL, :nazev, :cena, :datum_vydani, :platforma, :zanr, :vyrobce, :informace, :cesta_obr_maly, :cesta_obr_velky)");
          $stmt->bindParam(":nazev", $nazev_hry);
          $stmt->bindParam(":cena", $cena_hry);
          $stmt->bindParam(":datum_vydani", $datum_vydani);
          $stmt->bindParam(":platforma", $platforma);
          $stmt->bindParam(":zanr", $zanr);
          $stmt->bindParam(":vyrobce", $vyrobce);
          $stmt->bindParam(":informace", $informace);
          $stmt->bindParam(":cesta_obr_maly", $target_file);
          $stmt->bindParam(":cesta_obr_velky", $target_file2);
          $stmt->execute();
  
          $vse_ok = 1;
  
          if ($vse_ok == 1) {
              $nazev_hry = "";
              $cena_hry = "";
              $datum_vydani = "";
              $platforma = "";
              $zanr = "";
              $vyrobce = "";
              $informace = "";
              $obr_maly_cesta = "";
              $obr_velky_cesta = "";
              $vse_ok = 0;
          }
      }
  }
  
  if (isset($_POST["delete"])) {
      $stmt3 = $conn->prepare("DELETE FROM `hra` WHERE `nazev`=:nazev");
      $stmt3->bindParam(":nazev", $_POST["hra_del"]);
      $stmt3->execute();
  
      $delete_uspech = '<label class="alert-success">Odstraněno uspěšně</label>';
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer">
    <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">
</head>
<body>
    <nav class="navbar-detail">
        <div class="nav-top">
            <div class="nav-left">
                <i class="fa-solid fa-bars"></i>
                <a href="index.php">
                    <figure class="logo">
                        <img src="./images/logo.svg" alt="logo for vault games">
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
        <form method="post" enctype='multipart/form-data'>
            <div class="addInputs">
                <?php echo $err_nazev;?>
                <input name="nazev_add" class="addInput" placeholder="Název hry" value="<?php echo htmlspecialchars($nazev_hry);?>">
                <?php echo $err_cena;?>
                <input name="cena_add" class="addInput" placeholder="Cena v Kč" value="<?php echo htmlspecialchars($cena_hry);?>">
                <?php echo $err_datum;?>
                <input name="datum_add" type="date" class="addInput" placeholder="Datum vydání" value="<?php echo htmlspecialchars($datum_vydani);?>">
                <select name="platforma_add" class="addInput">
                    <option value="ps5" <?php if ($platforma == 'ps5') echo 'selected="selected"'; ?>>Playstation 5</option>
                    <option value="ps4" <?php if ($platforma == 'ps4') echo 'selected="selected"'; ?>>Playstation 4</option>
                    <option value="xbox" <?php if ($platforma == 'xbox') echo 'selected="selected"'; ?>>Xbox</option>
                    <option value="pc" <?php if ($platforma == 'pc') echo 'selected="selected"'; ?>>Počítač</option>
                    <option value="switch" <?php if ($platforma == 'switch') echo 'selected="selected"'; ?>>Nintento Switch</option>
                </select>
                <?php echo $err_zanr;?>
                <input name="zanr_add" class="addInput" placeholder="Žánr" value="<?php echo htmlspecialchars($zanr);?>">
                <?php echo $err_vyrobce;?>
                <input name="vyrobce_add" class="addInput" placeholder="Výrobce" value="<?php echo htmlspecialchars($vyrobce);?>">
                <?php echo $err_informace;?>
                <input name="informace_add" class="addInput" placeholder="Informace o hře" value="<?php echo htmlspecialchars($informace);?>">
                <label class="obr_poznamka">Obrazek malý (kartička index/košík)</label>
                <?php echo $err_soubor;?>
                <input name="fileToUpload" type="file" class="addInput" placeholder="Obrazek - malý">
                <label class="obr_poznamka">Obrazek velký (detail hry)</label>
                <?php echo $err_soubor2;?>
                <input name="fileToUpload2" type="file" class="addInput" placeholder="Obrazek - velký">
            </div>
            <input type="submit" name="add" class="addConfirmButton" value="Nahrát do databáze">
        </form>
    </div>
    <div class="addGameContainer">
        <h2 class="add_title">Odstranit hru</h2>
        <form method="post">
            <?php echo $delete_uspech;?>
            <select name="hra_del" class="addInput">
                <option value="">Vyberte hru pro smazání</option>
                <?php
                    $stmt2 = $conn->prepare("SELECT `nazev` FROM `hra`;");
                    $stmt2->execute();
                    $result = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        $nazev_hry_delete = $row["nazev"];
                        echo "<option value='$nazev_hry_delete'>$nazev_hry_delete</option>";
                    }
                ?>
            </select>
            <input type="submit" name="delete" class="addConfirmButton" value="Odstranit">
        </form>
    </div>
</body>
</html>