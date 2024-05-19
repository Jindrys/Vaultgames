<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "src/Exception.php";
require "src/PHPMailer.php";
require "src/SMTP.php";
include_once ("db/DBConnection.php");
session_start(); 

$userId = $_SESSION["user_id"];

$user_number_card = "";
$user_card_holder = "";
$user_expiration_date = "";
$user_cvv = "";

$err_phone = "";
$err_ulice = "";
$err_mesto = "";
$err_zeme = "";
$err_psc = "";
$err_number_card = "";
$err_card_holder = "";
$err_expiration_date = "";
$err_cvv = "";

$celkovaCena = 0;

$aktualni_datum = (new DateTime())->setTime(0, 0, 0);
$datum_vydani = (new DateTime($datum_vydani))->setTime(0, 0, 0);
$dateOrder = $datum_vydani->format('d.m.Y');
 
$stmt = $conn->prepare("SELECT `nick`, `jmeno`,`prijmeni`,`email`,`telefon`,`ulice_cp`,`mesto`,`psc`,`zeme` FROM `uzivatel` WHERE id_uzivatel=:id_uzivatel");
$stmt->bindParam(":id_uzivatel", $userId);        
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row) {
  $nick = $row['nick'];
  $user_firstname = $row['jmeno'];
  $user_lastname = $row['prijmeni'];
  //$email = $row['jmeno']; //...testovani -> osobni email
  $user_phone = $row['telefon'];
  $user_street_a_number = $row['ulice_cp'];
  $user_city = $row['mesto'];
  $user_state = $row['zeme'];
  $user_psc = $row['psc'];
}
$zacatekNicku = substr($nick, 0, 1);

$user_email = 'jindra.kopejtko@seznam.cz';
  
// Uložení kont. údajů

if (isset($_POST["ulozit_udaje"])) {
  
  if (!empty($_POST["telefon"])) {
    $regexp = "/^[0-9]{9}$/";
    $user_phone = trim($_POST["telefon"]);
    if (preg_match($regexp, $user_phone)) {
      $user_phone = trim($_POST["telefon"]);
      $err_phone = "";
    } else {  
      $err_phone = '<label class="alert-wrapper">Zadejte pouze čísla nebo jste zadali nedostatek čísel</label>';
      $user_phone = "";
    }
  } else {
    $err_phone = '<label class="alert-wrapper">Povinné</label>';
    $user_phone = "";
  } 

  if ($err_phone == "") {
    $stmt2 = $conn->prepare("UPDATE `uzivatel` SET `telefon`=:telefon WHERE id_uzivatel=:id_uzivatel");
    $stmt2->bindParam(":telefon", $user_phone);
    $stmt2->bindParam(":id_uzivatel", $userId);    
    $stmt2->execute();
  }
}

// Uložení dodacích údajů
if (isset($_POST["ulozit_adresu"])) {
  if (!empty($_POST["ulice"])) {
    $regexp = "/^[A-Za-zá-žÁ-Ž\s]+\s[0-9]+$/";
    if (preg_match($regexp, $_POST["ulice"])) {
      $user_street_a_number = trim($_POST["ulice"]);
    } else {  
      $err_ulice = '<label class="alert-wrapper">Zadejte opravdovou ulici</label>';
    }
  } else {
    $err_ulice = '<label class="alert-wrapper">Povinné</label>';
  }

  if (!empty($_POST["mesto"])) {
    $regexp = "/^[A-Za-zAEIÝÓŮÚŽŠČŘĎŤŇáéíýóůúžščřďťň]+$/";
    if (preg_match($regexp, $_POST["mesto"])) {
      $user_city = trim($_POST["mesto"]);
    } else {  
      $err_mesto = '<label class="alert-wrapper">Zadali jste město špatně</label>';
    }
  } else {
    $err_mesto = '<label class="alert-wrapper">Povinné</label>';
  }

  if (!empty($_POST["zeme"])) {
    $regexp = "/^[A-Za-zá-žÁ-Ž\s]+$/";
    if (preg_match($regexp, $_POST["zeme"])) {
      $user_state = trim($_POST["zeme"]);
    } else {  
      $err_zeme = '<label class="alert-wrapper">Zadali jste zemi špatně</label>';
    }
  } else {
    $err_zeme = '<label class="alert-wrapper">Vyplnění země je povinné</label>';
  }
  if (!empty($_POST["psc"])) {
    $regexp = "/^[0-9]{5}$/";
    if (preg_match($regexp, $_POST["psc"])) {
      $user_psc = trim($_POST["psc"]);
    } else {  
      $err_psc = '<label class="alert-wrapper">Zadejte psc správně</label>';
    }
  } else {
    $err_psc = '<label class="alert-wrapper">Vyplnění PSC je povinné</label>';
  }

  if ($err_ulice == "" && $err_mesto == "" && $err_zeme == "" && $err_psc == "") {
    $stmt3 = $conn->prepare("UPDATE `uzivatel` SET `ulice_cp`=:ulice,`mesto`=:mesto, `psc`=:psc,`zeme`=:zeme WHERE id_uzivatel=:id_uzivatel");
    $stmt3->bindParam(":ulice", $user_street_a_number);
    $stmt3->bindParam(":mesto", $user_city);
    $stmt3->bindParam(":zeme", $user_state);
    $stmt3->bindParam(":psc", $user_psc);
    $stmt3->bindParam(":id_uzivatel", $userId);    
    $stmt3->execute();
  }
}

// kontrola platebních údajů
if(isset($_POST['pay'])) {
  if (!empty($_POST["cislo_karty"])) {
    $regexp = "/^[0-9]{16}$/";
    $user_number_card = trim($_POST["cislo_karty"]);
    if (preg_match($regexp, $user_number_card)) {
      $user_number_card = trim($_POST["cislo_karty"]);
    } else {  
      $err_number_card = '<label class="alert-wrapper">Musí obsahovat 16 čísel</label>';
    }
  } else {
    $err_number_card = '<label class="alert-wrapper">Povinné</label>';
  }

  if (!empty($_POST["jmeno_drzitele"])) {
    $regexp = "/^[A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ][a-záčďéěíňóřšťúůýž]*\s[A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽ][a-záčďéěíňóřšťúůýž]*$/";
    $user_card_holder = trim($_POST["jmeno_drzitele"]);
    if (preg_match($regexp, $user_card_holder)) {
      $user_card_holder = trim($_POST["jmeno_drzitele"]);
    } else {  
      $err_card_holder = '<label class="alert-wrapper">Zadej te správné </label>';
    }
  } else {
    $err_card_holder = '<label class="alert-wrapper">Povinné</label>';
  }

  if (!empty($_POST["datum_expirace"])) {
    $regexp = "/^(0[1-9]|1[0-2])\/([0-9]{2})$/";
    $user_expiration_date = trim($_POST["datum_expirace"]);
    if (preg_match($regexp, $user_expiration_date)) {
      $user_expiration_date = trim($_POST["datum_expirace"]);
    } else {  
      $err_expiration_date = '<label class="alert-wrapper">Zadej te správné datum</label>';
    }
  } else {
    $err_expiration_date = '<label class="alert-wrapper">Povinné</label>';
  }
  if (!empty($_POST["cvv"])) {
    $regexp = "/^[0-9]{3,4}$/";
    $user_cvv = trim($_POST["cvv"]);
    if (preg_match($regexp, $user_cvv)) {
      $user_cvv = trim($_POST["cvv"]);
    } else {  
      $err_cvv = '<label class="alert-wrapper">Zadali jste nesprávný kód CVV</label>';
    }
  } else {
    $err_cvv = '<label class="alert-wrapper">Povinné</label>';
  }

  


}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Košík</title>
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
        <i class="fa-solid fa-calendar"></i>
        <i class="fa-solid fa-heart"></i>
        <a href="cart.php">
          <i class="fa-solid fa-cart-shopping"></i>
        </a>
        <a href="login.php">
          <div class="user"><?php echo $zacatekNicku;?></div>
        </a>
      </div>
    </div>
  </nav>

  <!-- Košík -->
  <div class="cart-wrapper">
    <div class="cart">
      <div class="product-container">
        <h2 class="product-text">Košík</h2>
        <div class="products">
        <?php
        $userId = $_SESSION["user_id"];
        $stmt = $conn->prepare("SELECT k.id_hra, h.nazev, h.obrazek, h.vyrobce, h.cena, k.pocet_kopii FROM kosik k JOIN hra h ON k.id_hra = h.id_hra WHERE k.id_uzivatel=:id_uzivatel");
        $stmt->bindParam(":id_uzivatel", $userId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
          echo  "<p>V košíku nic není.</p>";
        }
        foreach($result as $row) { 
          $gameId = $row['id_hra'];
          $nazev = $row['nazev'];
          $obrazek = $row['obrazek'];
          $vyrobce = $row['vyrobce'];
          $cena = $row['cena'];
          $pocet_kopii = $row['pocet_kopii'];
          echo "<div class='product'>
                <div class='product-wrapper'>
                <div class='productContainer'>
                <a href='detail.php?hra=$gameId'>
            <img class='productImage' src=$obrazek></img>
            </a>
            <div class='productInfo'>
              <div class='productTitle'>$nazev</div>
              <div class='productAuthor'>$vyrobce</div>
            </div>
            <form method='post'>
              <input type='hidden' name='id_hry' value=$gameId >
              <input type='submit' value='-' name='minus' class='addBtn'>
              <input type='number' value=$pocet_kopii name='pocet' class='pocet' disabled>
              <input type='submit' value='+' name='plus' class='addBtn'>
            </form>
            <div class='productPriceContainer'>
              <div class='productPrice'>$cena Kč</div>
              </div>
            </div>
          </div>
        </div>";
        $celkovaCena = $celkovaCena + $cena*$pocet_kopii;
          if ($celkovaCena == 0) {
            $zaplatitBtn = "<input class='paymentConfirmButton' value='Potvrdit a zaplatit' type='submit' name='pay' disabled>";
          }else{
            $zaplatitBtn = "<input class='paymentConfirmButton' value='Potvrdit a zaplatit' type='submit' name='pay'>";
          }
      }
      if(isset($_POST['pay'])) {
        if(empty($err_phone) && empty($err_ulice) && empty($err_mesto) && empty($err_zeme) && empty($err_psc) && empty($err_number_card) && empty($err_card_holder) && empty($err_expiration_date) && empty($err_cvv)) {

          // vytvoreni mailu a poslani
          $mail = new PHPMailer();
          $mail->isSMTP();
          $mail->Host = 'smtp.seznam.cz';
          $mail->Port = '465';
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = 'ssl';
          $mail->Username = 'vaultgames@email.cz';
          $mail->Password = 'Vaultgames123';
          $mail->From = 'vaultgames@email.cz';
          $mail->FromName = 'VaultGamesInfo';
          $mail->CharSet = 'utf-8';
          $mail->AddAddress($user_email);
          $mail->WordWrap = 50;
          $mail->IsHTML(true);
          $mail->Subject = 'Objednávka';
          $message_body = '<h2>Informace o objednávce</h2>
                          <p>==============================================</p>
                          <h3>Osobní údaje:</h3></br>
                          <p><b>Jméno:</b> '.$user_firstname.'</p>
                          <p><b>Příjmení:</b> '.$user_lastname.'</p>
                          <p><b>Email:</b> '.$user_email.'</p>
                      <p><b>Adresa:</b> '.$user_street_a_number.', '.$user_city.'</p>
                      <p><b>PSČ:</b> '.$user_psc.'</p>
                      <p><b>Tel. číslo:</b> '.$user_phone.'</p>
                      <p><b>Datum:</b> '.$dateOrder.'</p></br>
                      <p>==============================================</p>
                      <h3>Platba:</h3></br>
                      <p><b>Celková cena:</b> '.$celkovaCena.'  Kč</p>
                      <p><b>Platební metoda:</b> kartou</p>
                      <p><b>Číslo karty:</b> '.$user_number_card.'</p><br></br>';
          $mail->Body = $message_body;
          $mail->Send();
      
          $stmt7 = $conn->prepare("DELETE FROM `kosik` WHERE `id_uzivatel`=:id_uzivatel");
          $stmt7->bindParam(":id_uzivatel", $userId);
          $stmt7->execute();
          header('location:'.$_SERVER['PHP_SELF']);
          
        }
      }
        
        ?>
        <h2> Celkem: <?php echo $celkovaCena ?> Kč</h2>
        </div>
      </div>
      <?php 
if (isset( $_POST['plus'])) {
  $pocet_kopii++;
  $stmt4 = $conn->prepare("UPDATE `kosik` SET `pocet_kopii`=:pocet_kopii WHERE `id_hra`=:id_hra AND `id_uzivatel`=:id_uzivatel");
  $stmt4->bindParam(":pocet_kopii", $pocet_kopii);
  $stmt4->bindParam(":id_hra", $_POST["id_hry"]);
  $stmt4->bindParam(":id_uzivatel", $userId);
  $stmt4->execute();
  header('location:'.$_SERVER['PHP_SELF']);
  
}
if (isset($_POST['minus'])) {
  $pocet_kopii--;
  if ($pocet_kopii <= 0) {
    $stmt5 = $conn->prepare("DELETE FROM `kosik` WHERE `id_hra`=:id_hra AND `id_uzivatel`=:id_uzivatel");
    $stmt5->bindParam(":id_uzivatel", $userId);
    $stmt5->bindParam(":id_hra", $_POST["id_hry"]);
    $stmt5->execute();
    header('location:'.$_SERVER['PHP_SELF']);
    
  }else{
    $stmt6 = $conn->prepare("UPDATE `kosik` SET `pocet_kopii`=:pocet_kopii WHERE `id_hra`=:id_hra AND `id_uzivatel`=:id_uzivatel");
    $stmt6->bindParam(":pocet_kopii", $pocet_kopii);
    $stmt6->bindParam(":id_uzivatel", $userId);
    $stmt6->bindParam(":id_hra", $_POST["id_hry"]);
    $stmt6->execute();
    header('location:'.$_SERVER['PHP_SELF']);
    
  }
  
}
?>
      <!-- Kontaktní údaje -->
      <div class="paymentContainer">
        <h2 class="paymentTitle">Kontaktní údaje</h2>
        <div class="paymentInputs">
          <form class="paymentInputs" method="post">
            <input class="paymentInput" name="jmeno" type="text" value="<?php echo $user_firstname;?>" placeholder="Jméno" disabled/>
            <input class="paymentInput" name="prijmeni" type="text" value="<?php echo $user_lastname?>" placeholder="Přijmení" disabled/>
            <input class="paymentInput" name="email" type="email" value="<?php echo $user_email?>" placeholder="Email" disabled/> 
            <?php echo $err_phone;
            if (strlen($user_phone) == 0) {
              echo "<input class='paymentInput' name='telefon' type='tel' value='$user_phone' placeholder='Telefon'/>
              <input class='paymentConfirmButton' type='submit' value='Uložit' name='ulozit_udaje'/>";
            } else {
              echo "<input class='paymentInput' name='telefon' type='tel' value='$user_phone' placeholder='Telefon' disabled/>";
            }
            ?>   
            
          </form>
        </div>
      </div>

      <!-- Dodací adresa -->
      <div class="paymentContainer">
        <h2 class="paymentTitle">Dodací adresa</h2>
        <div class="paymentInputs">
          <form class="paymentInputs"  method="post">
            <?php echo $err_ulice;?>
            <input class="paymentInput" name="ulice" placeholder="Ulice a č.p." value="<?php echo $user_street_a_number;?>" />
            <?php echo $err_mesto;?>
            <input class="paymentInput" name="mesto" placeholder="Město" value="<?php echo $user_city;?>"/>
            <?php echo $err_zeme;?>
            <?php echo $err_psc;?>
            <div class="paymentGrid">
              <input class="paymentInput" name="zeme" placeholder="Země" value="<?php echo $user_state;?>"/>
              <input class="paymentInput" name="psc" placeholder="PSČ" value="<?php echo $user_psc;?>"/>
            </div>
            <input class="paymentConfirmButton" type="submit" value="Uložit" name="ulozit_adresu"/>
          </form>
        </div>
      </div>

      <!-- Platba -->
      <div class="paymentContainer">
        <h2 class="paymentTitle">Platební údaje</h2>
        <div class="paymentOptions">
          <button class="paymentButton">Credit card</button>
          <button class="paymentButton">Google Pay</button>
          <button class="paymentButton">Paypal</button>
          <button class="paymentButton">Apple pay</button>
        </div>
        <div class="paymentInputs">
          <form class="paymentInputs" method="post">
            <?php echo $err_number_card;?>
          <input class="paymentInput" name="cislo_karty" placeholder="Číslo karty" value="<?php echo $user_number_card;?>"/>
          <?php echo $err_card_holder;?>
          <input class="paymentInput" name="jmeno_drzitele" placeholder="Držitel karty" value="<?php echo $user_card_holder;?>"/>
          <?php echo $err_expiration_date;?>
          <?php echo $err_cvv;?>
          <div class="paymentGrid">
            <input class="paymentInput" name="datum_expirace" placeholder="Datum expirace" value="<?php echo $user_expiration_date;?>"/>
            <input class="paymentInput" name="cvv" placeholder="CVV" value="<?php echo $user_cvv;?>"/>
          </div>
          <?php echo $zaplatitBtn;?>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>