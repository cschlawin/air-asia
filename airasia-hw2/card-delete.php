<?php
// UC-05 / FR-05, BR-06: GET previews; only affirmative POST deletes.
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
  error_log('Air Asia delete lookup: ' . $exception->getMessage());
  show_error('Unable to load this gift card. Please try again later.', 503);
}
if ($card === null) {
  show_error('This gift card was not found. Return to the list.', 404);
}
$formError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (($_POST['confirm'] ?? null) !== 'yes') {
    $formError = 'Deletion was not confirmed. Nothing was changed.';
    http_response_code(422);
  } else {
    try {
      $statement = $pdo->prepare('DELETE FROM GIFTCARD WHERE cardId = :cardId');
      $statement->bindValue(':cardId', $cardId, PDO::PARAM_INT);
      $statement->execute();
      if ($statement->rowCount() === 0) {
        show_error('This gift card is no longer available.', 404);
      }
      header('Location: card-list.php', true, 303);
      exit;
    } catch (PDOException $exception) {
      error_log('Air Asia delete: ' . $exception->getMessage());
      $formError = 'Unable to delete this gift card. Please try again later.';
      http_response_code(503);
    }
  }
}
$pageTitle = 'Delete Gift Card';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>
      <a class="back-link" href="card-details.php?card_id=<?= e($cardId) ?>">
        ← Back to Gift Card Details
      </a>
      <section class="panel form-panel">
        <span class="eyebrow">GIFT CARD MANAGEMENT</span>
        <h1>Delete Gift Card</h1>
        <p>This will permanently remove the following gift card.</p>
        <dl class="specs">
          <div><dt>Card ID</dt><dd><?= e($cardId) ?></dd></div>
          <div><dt>Card Name</dt><dd><?= e($card['cardName']) ?></dd></div>
          <div><dt>Card Type</dt><dd><?= e($card['cardType']) ?></dd></div>
          <div>
            <dt>Card Value</dt>
            <dd>$<?= e(number_format($card['cardValue'], 2)) ?> USD</dd>
          </div>
          <div>
            <dt>Points Required</dt>
            <dd><?= e(number_format($card['points'])) ?> pts</dd>
          </div>
        </dl>
        <?php if ($formError !== null): ?>
          <p class="error-summary" role="alert"><?= e($formError) ?></p>
        <?php endif; ?>
        <form action="card-delete.php?card_id=<?= e($cardId) ?>" method="post">
          <div class="form-actions">
            <button class="button" name="confirm" value="yes" type="submit">
              Confirm Delete
            </button>
            <a class="button secondary"
              href="card-details.php?card_id=<?= e($cardId) ?>">Cancel</a>
          </div>
        </form>
      </section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
