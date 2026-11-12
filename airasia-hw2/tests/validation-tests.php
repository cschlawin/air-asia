<?php
// FR-09 / AT-06: pure validation only. No database include or writes.
require_once __DIR__ . '/../includes/validation.php';
$valid = [
  'cardName' => 'Amazon Gift Card',
  'cardType' => 'Retail',
  'cardValue' => '25.00',
  'points' => '2000',
];
$tests = [
  ['Valid gift card data', [], null],
  ['Blank card name', ['cardName' => '   '], 'cardName'],
  ['Invalid card type', ['cardType' => 'Altered'], 'cardType'],
  ['Zero card value', ['cardValue' => '0'], 'cardValue'],
  ['Negative card value', ['cardValue' => '-1'], 'cardValue'],
  ['Invalid points', ['points' => '2.5'], 'points'],
  [
    'Name over 100 characters',
    ['cardName' => str_repeat('a', 101)], 'cardName',
  ],
  ['100-character name accepted', ['cardName' => str_repeat('a', 100)], null],
  ['Unicode 100-character name', ['cardName' => str_repeat('é', 100)], null],
  ['Nonfinite card value', ['cardValue' => '1e309'], 'cardValue'],
  ['Zero points', ['points' => '0'], 'points'],
  ['Negative points', ['points' => '-1'], 'points'],
  ['Overflow points', ['points' => str_repeat('9', 50)], 'points'],
  ['Array field rejected', ['cardName' => ['unexpected']], 'cardName'],
];
foreach (CARD_TYPES as $type) {
  $tests[] = ['Allowed type: ' . $type, ['cardType' => $type], null];
}
$results = [];
foreach ($tests as [$label, $overrides, $expectedField]) {
  $errors = validate_card(array_replace($valid, $overrides))['errors'];
  $passed = $expectedField === null ? $errors === []
    : isset($errors[$expectedField]);
  $results[] = [$label, $passed];
}
$invalidIds = [null, '', '0', '-1', '1.5', 'abc', '1 OR 1=1', ['1'], '1e2'];
foreach ($invalidIds as $id) {
  $results[] = [
    'Reject ID: ' . json_encode($id), positive_integer($id) === null,
  ];
}
$results[] = ['Accept positive ID', positive_integer('12') === 12];
$results[] = ['Escape HTML output', e('<script>') === '&lt;script&gt;'];
$passedCount = count(array_filter($results, fn($row) => $row[1]));
if (PHP_SAPI === 'cli') {
  foreach ($results as [$label, $passed]) {
    echo ($passed ? 'PASS' : 'FAIL') . ': ' . $label . PHP_EOL;
  }
  echo $passedCount . '/' . count($results) . ' passed' . PHP_EOL;
  exit($passedCount === count($results) ? 0 : 1);
}
$pageTitle = 'Validation Tests';
$basePath = '../';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';
?>
      <section class="panel form-panel">
        <h1>Validation Tests</h1>
        <p>This harness does not connect to or modify the database.</p>
        <p><?= e($passedCount) ?>/<?= e(count($results)) ?> passed</p>
        <table class="test-table">
          <thead>
            <tr><th scope="col">Test</th><th scope="col">Result</th></tr>
          </thead>
          <tbody>
            <?php foreach ($results as [$label, $passed]): ?>
              <tr>
                <td><?= e($label) ?></td>
                <td class="<?= $passed ? 'test-pass' : 'test-fail' ?>">
                  <?= $passed ? 'PASS' : 'FAIL' ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
