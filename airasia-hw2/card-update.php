<?php
// UC-04 / FR-04: validated POST update, then selected details (HW3 flow).
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
  error_log('Air Asia update lookup: ' . $exception->getMessage());
  show_error('Unable to load this gift card. Please try again later.', 503);
}
if ($card === null) {
  show_error('This gift card was not found. Return to the list.', 404);
}
$values = [];
foreach (['cardName', 'cardType', 'cardValue', 'points'] as $field) {
  $values[$field] = (string) $card[$field];
}
$errors = [];
$formError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $result = validate_card($_POST);
  $values = $result['values'];
  $errors = $result['errors'];
  if ($errors === []) {
    try {
      $statement = $pdo->prepare(
        'UPDATE GIFTCARD SET cardName = :cardName, cardType = :cardType, '
        . 'cardValue = :cardValue, points = :points WHERE cardId = :cardId'
      );
      $statement->bindValue(':cardName', $values['cardName']);
      $statement->bindValue(':cardType', $values['cardType']);
      $statement->bindValue(':cardValue', $values['cardValue']);
      $statement->bindValue(':points', (int) $values['points'], PDO::PARAM_INT);
      $statement->bindValue(':cardId', $cardId, PDO::PARAM_INT);
      $statement->execute();
      if ($statement->rowCount() === 0) {
        show_error('This gift card is no longer available.', 404);
      }
      header('Location: card-details.php?card_id=' . $cardId, true, 303);
      exit;
    } catch (PDOException $exception) {
      error_log('Air Asia update: ' . $exception->getMessage());
      $formError = 'Unable to save changes. Please try again later.';
      http_response_code(503);
    }
  } else {
    http_response_code(422);
  }
}
$pageTitle = 'Update Gift Card';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>
      <a class="back-link" href="card-list.php">← Back to Gift Cards</a>
      <div class="form-layout">
        <section class="panel form-panel">
          <span class="eyebrow">GIFT CARD MANAGEMENT</span>
          <h1>Update Gift Card</h1>
          <p class="muted">
            Modify the selected gift card.
            All fields are required.
          </p>
          <p><strong>Card ID:</strong> <?= e($cardId) ?> (read-only)</p>
          <?php if ($formError !== null): ?>
            <p class="error-summary" role="alert"><?= e($formError) ?></p>
          <?php endif; ?>
          <?php if ($errors !== []): ?>
            <p class="error-summary" role="alert">
              Please correct the highlighted fields.
              Your entries are kept below.
            </p>
          <?php endif; ?>
          <form method="post"
            action="card-update.php?card_id=<?= e($cardId) ?>">
            <div class="field">
              <label for="card-name">
                Card Name <span aria-hidden="true">*</span>
              </label>
              <input id="card-name" name="cardName" type="text"
                maxlength="100" placeholder="Enter gift card name"
                value="<?= e($values['cardName']) ?>"
                <?= error_attributes($errors, 'cardName') ?> required>
              <?php field_error($errors, 'cardName'); ?>
            </div>
            <div class="field">
              <label for="card-type">
                Card Type <span aria-hidden="true">*</span>
              </label>
              <select id="card-type" name="cardType"
                <?= error_attributes($errors, 'cardType') ?> required>
                <option value="">Select card type</option>
                <?php foreach (CARD_TYPES as $type): ?>
                  <option value="<?= e($type) ?>"
                    <?= $values['cardType'] === $type ? 'selected' : '' ?>>
                    <?= e($type) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php field_error($errors, 'cardType'); ?>
            </div>
            <div class="form-row">
              <div class="field">
                <label for="card-value">
                  Card Value (USD) <span aria-hidden="true">*</span>
                </label>
                <input id="card-value" name="cardValue" type="number"
                  min="0" step="any" placeholder="25.00"
                  value="<?= e($values['cardValue']) ?>"
                  <?= error_attributes($errors, 'cardValue') ?> required>
                <?php field_error($errors, 'cardValue'); ?>
              </div>
              <div class="field">
                <label for="points">
                  Points Required <span aria-hidden="true">*</span>
                </label>
                <input id="points" name="points" type="number"
                  min="1" step="1" placeholder="2000"
                  value="<?= e($values['points']) ?>"
                  <?= error_attributes($errors, 'points') ?> required>
                <?php field_error($errors, 'points'); ?>
              </div>
            </div>
            <div class="form-actions">
              <button class="button" type="submit">
                Save Changes
              </button>
              <a class="button secondary"
                href="card-details.php?card_id=<?= e($cardId) ?>">Cancel</a>
            </div>
          </form>
        </section>
        <aside class="form-aside">
          <span class="eyebrow">A LITTLE SOMETHING EXTRA</span>
          <h2>Great rewards start here.</h2>
          <img src="images/gift-card.png" alt="Red rewards gift card">
          <p>
            Give every reward a clear name, category, value, and points
            requirement.
          </p>
        </aside>
      </div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
