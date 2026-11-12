<?php
// UC-01 / FR-01: read the saved catalog; authentication is HW4 work.
require_once __DIR__ . '/includes/db.php';
if ($dbError !== null) {
  show_error($dbError, 503);
}
try {
  $cards = $pdo->query(
    'SELECT cardId, cardName, cardType, cardValue, points '
    . 'FROM GIFTCARD ORDER BY cardId'
  )->fetchAll();
} catch (PDOException $exception) {
  error_log('Air Asia list: ' . $exception->getMessage());
  show_error('Unable to load gift cards. Please try again later.', 503);
}
$pageTitle = 'Gift Cards';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>
      <section class="page-heading">
        <div>
          <span class="eyebrow">DISCOVER YOUR NEXT REWARD</span>
          <h1>Gift Cards</h1>
          <p class="muted">Explore gift cards from your favorite brands.</p>
        </div>
        <a class="button" href="card-add.php">+ Add Gift Card</a>
      </section>
      <?php if ($cards === []): ?>
        <section class="panel form-panel">
          <h2>No gift cards yet</h2>
          <p>Add a gift card to start your catalog.</p>
        </section>
      <?php else: ?>
        <div class="card-grid">
          <?php foreach ($cards as $card): ?>
            <article class="gift-card">
              <a class="card-image"
                href="card-details.php?card_id=<?= e($card['cardId']) ?>"
                aria-label="View <?= e($card['cardName']) ?> details">
                <img src="images/gift-card.png" alt="Rewards gift card">
                <span class="merchant"><?= e($card['cardName']) ?></span>
              </a>
              <div class="card-body">
                <span class="category"><?= e($card['cardType']) ?></span>
                <h2>
                  <a href="card-details.php?card_id=<?= e($card['cardId']) ?>">
                    <?= e($card['cardName']) ?>
                  </a>
                </h2>
                <p class="points">
                  <?= e(number_format($card['points'])) ?> <span>pts</span>
                </p>
                <a class="text-link"
                  href="card-details.php?card_id=<?= e($card['cardId']) ?>">
                  View details <span aria-hidden="true">→</span>
                </a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
