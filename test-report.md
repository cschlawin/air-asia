# HW3 Test Report

Test date: November 12, 2026. Tester: OpenAI ChatGPT / Codex.

## Environment and method

Executed PHP 8.4.27 CLI and CGI with PDO SQLite in the assistant's Linux
workspace. CGI requests used REQUEST_METHOD, QUERY_STRING, form-encoded POST
bodies and the actual PHP files. Assertions inspected generated HTML, HTTP
status/Location headers and SQLite rows. Workflow mutations used an isolated
temporary copy of the application/database, not the submitted rewards.db.
No test account or workflow test card was left in the submitted database.

The PASS results below apply to **server-side execution through PHP CGI**.
They do not mean a visual browser test or GitHub Codespaces run was completed.
Chromium could not launch because socket creation was denied by the local
execution environment; the browser connection attempt returned no usable
session. Codespaces/manual browser verification is **NOT RUN / pending**.

## Required test cases

### T01

- **Steps/input:** Run `php airasia-hw2/tests/validation-tests.php` and request `/tests/validation-tests.php`. Compare database bytes/rows before and after.
- **Expected:** All validation cases show PASS; harness never changes data.
- **Actual:** 29/29 CLI and rendered-CGI validation checks passed; database bytes and rows unchanged.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

### T02

- **Steps/input:** Request `/card-list.php` with the seeded database; inspect each image, card name, points, stylesheet and selected-details URL.
- **Expected:** 20 cards render from GIFTCARD; asset and navigation targets exist; shared layout appears once.
- **Actual:** List rendered 20 database cards; all local image, stylesheet and link targets exist; shared layout appears once.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

### T03

- **Steps/input:** Request `/card-details.php?card_id=N` for IDs 1-20; compare with SQLite.
- **Expected:** Each selected record displays cardId, cardName, cardType, cardValue and points with update/delete/list navigation.
- **Actual:** All 20 seeded IDs rendered their own names/types, read-only attributes and selected update/delete links.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

### T04

- **Steps/input:** GET Add; POST cardName=Coursework Test Card, cardType=Retail, cardValue=12.50, points=1250. Inspect SQLite and redirect.
- **Expected:** GET changes nothing; POST inserts one generated-ID row and redirects to card-list.php.
- **Actual:** GET made no changes; valid POST inserted one row (ID 21) and returned 303 to card-list.php.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

### T05

- **Steps/input:** Open Update for the new card; POST Updated Coursework Card, Travel, 30.25, 3000. Reopen details; resubmit unchanged values.
- **Expected:** Prefilled fields/read-only ID; four values persist; 303 redirects to the selected details, including unchanged resubmit.
- **Actual:** Prefilled fields and read-only ID verified; POST persisted all four changes and returned 303 to selected details; unchanged resubmit also passed.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

### T06

- **Steps/input:** Open Delete with GET and confirm=yes in the URL; follow Cancel. POST no/missing/array confirmation, then confirm=yes. Compare rows and accounts.
- **Expected:** GET/Cancel do not delete; unconfirmed POST is rejected; affirmative POST deletes only selected card and redirects to List.
- **Actual:** GET plus confirm=yes and Cancel preserved record; missing/no/array POST confirmation rejected; affirmative POST deleted only selected test card and returned 303 to list.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

### T07

- **Steps/input:** Submit 13 invalid payloads to both Add and Update: blank/101-character name, altered type, zero/negative/NaN/infinite value, fractional/negative/zero/overflow points, name/points arrays. Also submit a script/quote-containing name.
- **Expected:** 422 field errors and zero writes for invalid data; valid quote/markup text is stored as data and escaped on every view; safe entered values are retained.
- **Actual:** 13 altered/invalid payloads rejected on both Add and Update (26 checks), with no writes; script/quote-containing name safely persisted and escaped in list/details/update/delete; safe values retained after rejection.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

### T08

- **Steps/input:** GET and POST Details/Update/Delete with missing, empty, zero, negative, fractional, alphabetic, exponent, SQL-like, array, overflowing and unknown ID=999999.
- **Expected:** 400 or 404 plus a helpful return link; no warnings, raw SQL errors or unintended writes.
- **Actual:** 11 missing/malformed/array/overflow/unknown ID variants safely rejected on Details, Update and Delete for GET and POST (66 checks); zero unintended changes.
- **Status:** PASS (automated PHP CLI/CGI, not manual browser).

## Additional checks actually performed

- All 12 PHP files passed `php -l`, including the unchanged login prototype.
- SQLite integrity_check returned `ok`; the delivered database has exactly
  20 GIFTCARD and five ACCOUNT rows and only those two application tables
  (SQLite also maintains its internal sqlite_sequence table).
- Running schema and seed twice preserved rows/counts. Rerunning seed after
  editing a seed record preserved the edit instead of overwriting it.
- The harness left database bytes and all rows unchanged. The CRUD test
  suite left ACCOUNT data unchanged.
- Temporarily removing the test database produced a friendly 503 response,
  without creating an empty replacement or printing raw connection details.
- All source/asset URLs in List resolve to actual project files. Image
  appearance and responsive visual layout still need a browser check.

## Defects and review decisions

1. Existing gift-card pages printed C-style comments before HTML. This can
   expose comments and interfere with Location headers. Converted affected
   page comments to PHP comments before output. Retest: Add, Update and
   Delete returned the required 303 Location headers without leading text.
2. The initial T03 assertion compared H&M literally with escaped HTML and
   incorrectly failed. Corrected the test comparison to decode HTML text;
   output escaping was retained. Retest: all 20 selected records passed.
3. Existing static Update returned to List and there was no Delete page.
   Implemented the current HW3 flow and confirmed POST-only deletion.
   Retest: T05-T06 passed.
4. Card values have no documented two-decimal storage restriction. Used
   positive finite numeric validation with `step="any"`; display remains
   two-decimal USD formatting. The old HTML-only constraints do not replace
   PHP validation. Retest: harness and invalid-input checks passed.

## Manual Codespaces/browser checks still required

These steps are instructions, not recorded results. Actual status: NOT RUN.

1. Open the repository in Codespaces; pull main, verify PHP 8 and pdo_sqlite,
   and run the setup/count checks in README. Start the server on port 8000.
2. Open `/card-list.php` directly without logging in. Confirm all 20 cards
   display names, points and images with no broken layout on desktop/mobile.
3. Open two different cards and confirm the details differ and show all
   five attributes. Use List/Add navigation and each Back/Cancel link.
4. Add a uniquely named temporary test card, update it and confirm the
   redirected details show the changes. Check field errors and safe input
   preservation for invalid submissions (disable native browser validation
   if intentionally testing server rejection).
5. Open Delete, cancel and verify the card remains. Then confirm deletion;
   check it disappears and the original 20 cards remain. Do not delete a
   seeded card while checking the submission's minimum count.
6. Visit `card-details.php?card_id=abc` and `?card_id=999999`; confirm helpful
   errors, then open `/tests/validation-tests.php` and confirm 29/29 PASS.
7. Record actual browser/Codespaces outcomes here, including any fixes and
   retests. Commit your own changes and database state before submission.

## Submission status

Code and database tests described above were actually executed. The assistant
will verify the GitHub commit after publishing; an unverified push or Canvas
submission is not treated as a passing test. Canvas submission remains the
student's responsibility. This report does not certify instructor approval.
