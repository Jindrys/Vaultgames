<?php
  include_once ("db/DBConnection.php");
  session_start();
  if (!isset($_SESSION["role"])) {
    header('location:index.php');
  }
  if($_SESSION["role"] == 0) {
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
    if ($err_nazev == "" && $err_cena == "" && $err_datum == "" && $err_zanr == "" && $err_vyrobce == "" && $err_informace == "") {

    // ulozeni obrazku
    $target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image

  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }


// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
$target_dir2 = "images_detail/";
$target_file2 = $target_dir2 . basename($_FILES["fileToUpload2"]["name"]);
$uploadOk2 = 1;
$imageFileType2 = strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image

  $check2 = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
  if($check2 !== false) {
    echo "File is an image - " . $check2["mime"] . ".";
    $uploadOk2 = 1;
  } else {
    echo "File is not an image.";
    $uploadOk2 = 0;
  }


// Check if file already exists
if (file_exists($target_file2)) {
  echo "Sorry, file already exists.";
  $uploadOk2 = 0;
}

// Check file size
if ($_FILES["fileToUpload2"]["size"] > 5000000) {
  echo "Sorry, your file is too large.";
  $uploadOk2 = 0;
}

// Allow certain file formats
if($imageFileType2 != "jpg" && $imageFileType2 != "png" && $imageFileType2 != "jpeg"
&& $imageFileType2 != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk2 = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk2 == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file2)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload2"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
    } else { 

    }

if ($err_nazev == "" && $err_cena == "" && $err_datum == "" && $err_zanr == "" && $err_vyrobce == "" && $err_informace == "") {
  $stmt = $conn->prepare("INSERT INTO `hra`(`id_hra`, `nazev`, `cena`, `datum_vydani`, `platforma`, `zanr`, `výrobce`, `informace`, `obrazek`, `obrazek_detail`) VALUES (NULL, :nazev, :cena, :datum, :platforma, :zanr, :vyrobce, :informace, :cesta_obr_maly, :cesta_obr_velky)");
      $stmt->bindParam(":nazev", $nazev_hry);
      $stmt->bindParam(":cena", $cena_hry);
      $stmt->bindParam(":datum", $datum_vydani);
      $stmt->bindParam(":platforma", $platforma);
      $stmt->bindParam(":zanr", $zanr);
      $stmt->bindParam(":vyrobce", $vyrobce);
      $stmt->bindParam(":informace", $informace);
      $stmt->bindParam(":cesta_obr_maly", $target_file);
      $stmt->bindParam(":cesta_obr_velky", $target_file2);
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
    <form method="post" enctype='multipart/form-data'>
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
      
      <input name="fileToUpload" type="file" class="addInput" placeholder="Obrazek - malý" />
      
      <input name="fileToUpload2" type="file" class="addInput" placeholder="Obrazek - velký" />
    </div>
    <input type="submit" name="add" class="addConfirmButton" value="Nahrát do databáze"/>
    </form>
    
  </div>
</body>
</html>
