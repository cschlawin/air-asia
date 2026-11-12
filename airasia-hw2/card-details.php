<?php
// UC-02 / FR-02, BR-05: selected record, read-only attributes, safe IDs.
require_once __DIR__ . '/includes/db.php';
$cardId = positive_integer($_GET['card_id'] ?? null);
if ($cardId === null) {
  show_error('Choose a gift card using a valid positive integer card ID.');
}
if ($dbError !== null) {
  show_error($dbError, 503);
}
try {
  $card = find_card($pdo, $cardId);
} catch (PDOException $exception) {
  error_log('Air Asia details: ' . $exception->getMessage());
  show_error('Unable to load this gift card. Please try again later.', 503);
}
if ($card === null) {
  show_error('This gift card was not found. Return to the list.', 404);
}
$pageTitle = 'Gift Card Details';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>
      <a class="back-link" href="card-list.php">← Back to Gift Cards</a>
      <section class="panel details-layout">
        <div class="detail-art">
          <img src="images/gift-card.png" alt="Rewards gift card">
          <span class="detail-merchant"><?= e($card['cardName']) ?></span>
        </div>
        <div class="detail-copy">
          <span class="eyebrow">GIFT CARD DETAILS</span>
          <h1><?= e($card['cardName']) ?></h1>
          <p class="muted">Explore this gift card from the rewards catalog.</p>
          <dl class="specs">
            <div><dt>Card ID</dt><dd><?= e($card['cardId']) ?></dd></div>
            <div><dt>Card Name</dt><dd><?= e($card['cardName']) ?></dd></div>
            <div><dt>Card Type</dt><dd><?= e($card['cardType']) ?></dd></div>
            <div>
              <dt>Card Value</dt>
              <dd>$<?= e(number_format($card['cardValue'], 2)) ?> USD</dd>
            </div>
            <div>
              <dt>Points Required</dt>
              <dd class="points">
                <?= e(number_format($card['points'])) ?> <span>pts</span>
              </dd>
            </div>
          </dl>
          <div class="form-actions">
            <a class="button"
              href="card-update.php?card_id=<?= e($cardId) ?>">
              Update Gift Card
            </a>
            <a class="button secondary"
              href="card-delete.php?card_id=<?= e($cardId) ?>">
              Delete Gift Card
            </a>
          </div>
          <a class="back-link return-link" href="card-list.php">
            Return to Gift Card List
          </a>
        </div>
      </section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
