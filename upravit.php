<?php
include_once("db/DBConnection.php");
session_start();

if (!isset($_SESSION["role"])) {
    header('location:index.php');
}

if (isset($_SESSION["user_id"])) {
    $idUser = $_SESSION["user_id"];
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

$err_nick= "";
$err_jmeno = "";
$err_prijmeni = "";
$err_email = "";
$err_telefon = "";
$err_ulice = "";
$err_mesto = "";
$err_psc = "";
$err_zeme = "";

if (isset($_POST["save"])) {
    // Nickname validation
    if (!empty($_POST["nick"])) {
        $regexp = "/^[A-Za-z0-9AEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
        if (preg_match($regexp, $_POST["nick"])) {
            $nick = trim($_POST["nick"]);
        } else {  
            $err_nick = '<label class="alert-wrapper">Pouze písmena a číslice</label>'; 
        }   
    } else {
        $err_nick = '<label class="alert-wrapper">Povinné</label>';
    }

    // First name validation
    if (!empty($_POST["jmeno"])) {
        $regexp = "/^[A-Za-zAEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
        if (preg_match($regexp, $_POST["jmeno"])) {
            $jmeno = trim($_POST["jmeno"]);
        } else {  
            $err_jmeno = '<label class="alert-wrapper">Zadejte opravdové jméno</label>';
        }
    } else {
        $err_jmeno = '<label class="alert-wrapper">Povinné</label>';
    }

    // Last name validation
    if (!empty($_POST["prijmeni"])) {
        $regexp = "/^[A-Za-zAEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
        if (preg_match($regexp, $_POST["prijmeni"])) {
            $prijmeni = trim($_POST["prijmeni"]);
        } else {  
            $err_prijmeni = '<label class="alert-wrapper">Zadejte opravdové příjmení</label>';
        }
    } else {
        $err_prijmeni = '<label class="alert-wrapper">Povinné</label>';
    }
    
    // Email validation
    if (!empty($_POST["email"])) {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = trim($_POST["email"]);
            
            if ($email == $row['email']) {
                $email = $row['email'];
            } else {
                $stmt2 = $conn->prepare("SELECT email FROM `uzivatel` WHERE email=:email");
                $stmt2->bindParam(':email', $email);
                $stmt2->execute();
                $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                
                if ($result2) {
                    $err_email = '<label class="alert-wrapper">Tato emailová adresa je již použita</label>';
                } else {
                    $email = trim($_POST["email"]);
                }
            }
        } else {
            $err_email = '<label class="alert-wrapper">Neplatná e-mailová adresa</label>';
        }
    } else {
        $err_email = '<label class="alert-wrapper">E-mailová adresa je povinná</label>';
    }

    // Phone number validation
    if (!empty($_POST["telefon"])) {
        $regexp = "/^[0-9]{9}$/";
        $telefon = trim($_POST["telefon"]);
        if (preg_match($regexp, $telefon)) {
          $telefon = trim($_POST["telefon"]);
        } else {  
          $err_telefon = '<label class="alert-wrapper">Zadejte pouze čísla nebo jste zadali nedostatek čísel</label>';
        }
    }
    
    // Street address validation
    if (!empty($_POST["ulice"])) {
        $regexp = "/^[A-Za-zá-žÁ-Ž\s]+\s[0-9]+$/";
        if (preg_match($regexp, $_POST["ulice"])) {
          $ulice = trim($_POST["ulice"]);
        } else {  
          $err_ulice = '<label class="alert-wrapper">Zadejte opravdovou ulici</label>';
        }
    }
    
    // City validation
    if (!empty($_POST["mesto"])) {
        $regexp = "/^[A-Za-zAEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
        if (preg_match($regexp, $_POST["mesto"])) {
          $mesto = trim($_POST["mesto"]);
        } else {  
          $err_mesto = '<label class="alert-wrapper">Zadali jste město špatně</label>';
        }
    }
    
    // Country validation
    if (!empty($_POST["zeme"])) {
        $regexp = "/^[A-Za-zá-žÁ-Ž\s]+$/";
        if (preg_match($regexp, $_POST["zeme"])) {
          $zeme = trim($_POST["zeme"]);
        } else {  
          $err_zeme = '<label class="alert-wrapper">Zadali jste zemi špatně</label>';
        }
    }

    // Postal code validation
    if (!empty($_POST["psc"])) {
        $regexp = "/^[0-9]{5}$/";
        if (preg_match($regexp, $_POST["psc"])) {
          $psc = trim($_POST["psc"]);
        } else {  
          $err_psc = '<label class="alert-wrapper">Zadejte psc správně</label>';
        }
    }
    
    if (empty($err_nick) && empty($err_jmeno) && empty($err_prijmeni) && empty($err_email) && empty($err_telefon) && empty($err_ulice) && empty($err_mesto) && empty($err_psc) && empty($err_zeme)) {
        $stmt = $conn->prepare("UPDATE `uzivatel` SET `nick`=:nick, `jmeno`=:jmeno, `prijmeni`=:prijmeni, `email`=:email, `telefon`=:telefon, `ulice_cp`=:ulice, `mesto`=:mesto, `psc`=:psc, `zeme`=:zeme WHERE `id_uzivatel`=:id_uzivatel");
        $stmt->bindParam(":nick", $nick);
        $stmt->bindParam(":jmeno", $jmeno);
        $stmt->bindParam(":prijmeni", $prijmeni);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefon", $telefon);
        $stmt->bindParam(":ulice", $ulice);
        $stmt->bindParam(":mesto", $mesto);
        $stmt->bindParam(":psc", $psc);
        $stmt->bindParam(":zeme", $zeme);
        $stmt->bindParam(":id_uzivatel", $idUser);
        $stmt->execute();
        header('location:user.php?update=success');
    }
}
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
        </div>
    </nav>
    
    <main class="user-main">
        <div class="container">
            <div class="user-wrap">
                <div class="main-left">
                    <form method="post">
                        <div class="user-info">
                            <?php echo $err_nick;?>
                            <div class="username">
                                <h3 class="subtitle">Prezdívka:</h3>
                                <input class="user-update" type="text" name="nick" value="<?php echo $nick; ?>" placeholder="Přesdívka..."/>
                            </div>
                            <?php echo $err_email;?>
                            <div class="email">
                                <h3 class="subtitle">Email:</h3>
                                <input class="user-update" type="email" name="email" value="<?php echo $email; ?>" placeholder="Email..."/>
                            </div>
                            <?php echo $err_jmeno;?>
                            <div class="full-name">
                                <h3 class="subtitle">Jméno:</h3>
                                <input class="user-update" type="text" name="jmeno" value="<?php echo $jmeno; ?>" placeholder="Jméno..."/>
                            </div>
                            <?php echo $err_prijmeni;?>
                            <div class="full-name">
                                <h3 class="subtitle">Prijmení:</h3>
                                <input class="user-update" type="text" name="prijmeni" value="<?php echo $prijmeni; ?>" placeholder="Přijmení..."/>
                            </div>
                            <?php echo $err_telefon;?>
                            <div class="full-name">
                                <h3 class="subtitle">Telefon:</h3>
                                <input class="user-update" type="text" name="telefon" value="<?php echo $telefon; ?>" placeholder="Telefon..."/>
                            </div>
                            <?php echo $err_ulice;?>
                            <div class="full-name">
                                <h3 class="subtitle">Ulice:</h3>
                                <input class="user-update" type="text" name="ulice" value="<?php echo $ulice_cp; ?>" placeholder="Ulice a číslo popisné..."/>
                            </div>
                            <?php echo $err_psc;?>
                            <div class="full-name">
                                <h3 class="subtitle">PSC:</h3>
                                <input class="user-update" type="text" name="psc" value="<?php echo $psc; ?>" placeholder="Poštovní směrovací číslo..."/>
                            </div>
                            <?php echo $err_mesto;?>
                            <div class="full-name">
                                <h3 class="subtitle">Mesto:</h3>
                                <input class="user-update" type="text" name="mesto" value="<?php echo $mesto; ?>" placeholder="Město..."/>
                            </div>
                            <?php echo $err_zeme;?>
                            <div class="full-name">
                                <h3 class="subtitle">Zeme:</h3>
                                <input class="user-update" type="text" name="zeme" value="<?php echo $zeme; ?>" placeholder="Země..."/>
                            </div>
                            <input class="updateButton" type="submit" name="save" value="Uložit">
                        </div>
                    </form>
                </div>
                <div class="main-right">
                    <img src="./images/user.svg" alt="fallout boy with thumb" />
                </div>
            </div>
        </div>
    </main>
</body>
</html>
