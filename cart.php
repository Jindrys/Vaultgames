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

  <!-- Košík -->
  <div class="cart-wrapper">
    <div class="cart">
      <div class="product-container">
        <h2 class="product-text">Košík</h2>
        <div class="products">
          <div class="product">
            <span class="something"></span>
            <div class="something2">
              <div class="productContainer">
                <img class="productImage" src="./images/fifa.png"></img>
                <div class="productInfo">
                  <div class="productTitle">UI DESIGN, A USER APPROACH</div>
                  <div class="productAuthor">Klara Weaver</div>
                </div>
                <div class="productPriceContainer">
                  <div class="productPrice">$49</div>
                  <svg class="productIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18"></path>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dodací adresa -->
      <div class="paymentContainer">
        <h2 class="paymentTitle">Dodací adresa</h2>

        <div class="paymentInputs">
          <input class="paymentInput" placeholder="Ulice a č.p." />
          <input class="paymentInput" placeholder="Město" />
          <div class="paymentGrid">
            <input class="paymentInput" placeholder="Země" />
            <input class="paymentInput" placeholder="PSČ" />
          </div>

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
          <input class="paymentInput" placeholder="Card number" />
          <input class="paymentInput" placeholder="Card holder" />
          <div class="paymentGrid">
            <input class="paymentInput" placeholder="Expiration date" />
            <input class="paymentInput" placeholder="CVV" />
          </div>
          
          <button class="paymentConfirmButton">Potvrdit a zaplatit</button>
        </div>
      </div>
    </div>
  </div>
</body>