<?php
// UC-03 / FR-03: validate on the server before inserting via PDO.
require_once __DIR__ . '/includes/db.php';
if ($dbError !== null) {
  show_error($dbError, 503);
}
$values = [
  'cardName' => '', 'cardType' => '', 'cardValue' => '', 'points' => '',
];
$errors = [];
$formError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $result = validate_card($_POST);
  $values = $result['values'];
  $errors = $result['errors'];
  if ($errors === []) {
    try {
      $statement = $pdo->prepare(
        'INSERT INTO GIFTCARD (cardName, cardType, cardValue, points) '
        . 'VALUES (:cardName, :cardType, :cardValue, :points)'
      );
      $statement->bindValue(':cardName', $values['cardName']);
      $statement->bindValue(':cardType', $values['cardType']);
      $statement->bindValue(':cardValue', $values['cardValue']);
      $statement->bindValue(':points', (int) $values['points'], PDO::PARAM_INT);
      $statement->execute();
      header('Location: card-list.php', true, 303);
      exit;
    } catch (PDOException $exception) {
      error_log('Air Asia add: ' . $exception->getMessage());
      $formError = 'Unable to add this gift card. Please try again later.';
      http_response_code(503);
    }
  } else {
    http_response_code(422);
  }
}
$pageTitle = 'Add Gift Card';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>
      <a class="back-link" href="card-list.php">← Back to Gift Cards</a>
      <div class="form-layout">
        <section class="panel form-panel">
          <span class="eyebrow">GIFT CARD MANAGEMENT</span>
          <h1>Add Gift Card</h1>
          <p class="muted">
            Enter the details for a new gift card.
            All fields are required.
          </p>
          <?php if ($formError !== null): ?>
            <p class="error-summary" role="alert"><?= e($formError) ?></p>
          <?php endif; ?>
          <?php if ($errors !== []): ?>
            <p class="error-summary" role="alert">
              Please correct the highlighted fields.
              Your entries are kept below.
            </p>
          <?php endif; ?>
          <form action="card-add.php" method="post">
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
                Add Gift Card
              </button>
              <a class="button secondary" href="card-list.php">Cancel</a>
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
