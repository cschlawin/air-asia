/* 
 * Air Asia Rewards Gift Card Management System
 * card-list.php
 * 
 * This file displays a list of available gift cards in the system.
 * Each card includes details such as merchant name, category, points required, and a link to view more details.
 * Users can also add new gift cards via the "Add Gift Card" button.
 * 
 * Note: This is a static prototype. Changes are not saved.
 */
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Air Asia</title>
    <link rel="stylesheet" href="css/styles.css" />
  </head>
  <body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header">
      <div class="header-inner">
        <div class="brand" aria-label="Air Asia Rewards">
          <span class="brand-mark">AirAsia</span
          ><span
            ><strong>Air Asia Rewards</strong
            ><small>Gift Card Redemption</small></span
          >
        </div>
        <nav aria-label="Main navigation">
          <span class="nav-label">Welcome to Rewards</span>
        </nav>
      </div>
    </header>
    <main id="main">
      <div class="login-layout">
        <section class="welcome">
          <span class="eyebrow">AIR ASIA REWARDS</span>
          <h1>Your points.<br />Your next reward.</h1>
          <p>Explore gift cards from brands you love, all in one place.</p>
          <div class="welcome-art" aria-hidden="true">
            <img src="images/gift-card.png" alt="" />
          </div>
          <span class="welcome-caption"
            >A little reward. A world of possibilities.</span
          >
        </section>
        <section class="panel login-panel" aria-labelledby="login-title">
          <span class="eyebrow">LET’S GET STARTED</span>
          <h2 id="login-title">Welcome back</h2>
          <p class="muted">Sign in to explore your rewards.</p>
          <form action="card-list.php" method="get">
            <div class="field">
              <label for="username"
                >Username <span aria-hidden="true">*</span></label
              ><input
                id="username"
                type="text"
                placeholder="Enter your username"
                autocomplete="username"
                required
              />
            </div>
            <div class="field">
              <label for="password"
                >Password <span aria-hidden="true">*</span></label
              ><input
                id="password"
                type="password"
                placeholder="Enter your password"
                autocomplete="current-password"
                required
              />
            </div>
            <button class="button full" type="submit">
              Login <span aria-hidden="true">→</span>
            </button>
          </form>
          <p class="demo-note">
            Coursework demo: enter any username and password to continue. Use
            sample credentials only.
          </p>
        </section>
      </div>
    </main>
    <footer class="site-footer">
      <span>Air Asia Rewards Redemption System</span
      ><span>IS 6465 · Static coursework prototype</span>
    </footer>
  </body>
</html>
