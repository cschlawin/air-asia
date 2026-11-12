# Air Asia Rewards Gift Card Website

IS 6465 HW3: PHP 8, SQLite, PDO, HTML and the existing Air Asia CSS.
The original application remains in **airasia-hw2/**; its name is retained
so existing links and Codespaces commands continue to work. There is one
application root and one database.

## Quick database access

Run these commands in the **Codespaces terminal**, starting in the
repository root (`/workspaces/air-asia`):

```bash
cd airasia-hw2/database
sqlite3 rewards.db
```

At the `sqlite>` prompt, inspect the database:

```sql
.tables
.schema GIFTCARD
.headers on
.mode column
SELECT * FROM GIFTCARD LIMIT 5;
SELECT COUNT(*) AS account_count FROM ACCOUNT;
SELECT COUNT(*) AS gift_card_count FROM GIFTCARD;
.quit
```

`.tables` should list `ACCOUNT` and `GIFTCARD`. The supplied seed database
contains five accounts and 20 gift cards; counts can change after CRUD tests.
Commands beginning with a dot are SQLite commands, not Bash commands.

After `.quit`, you are back in Bash in the database folder. To initialize
the tables or deliberately rerun the sample seed data from that folder:

```bash
sqlite3 rewards.db < schema.sql
sqlite3 rewards.db < seed.sql
```

The committed database is already initialized; these setup commands are
optional. Rerunning the seed preserves existing IDs but can restore deleted
sample records. Use `sqlite3` only once per command. Bash resolves
`< schema.sql` relative to your current folder, so running that short
command from the repository root causes “schema.sql: No such file or
directory.”

To return to the repository root after using the database folder:

```bash
cd ../..
```

Alternatively, open the database directly **from the repository root**
without changing folders:

```bash
sqlite3 airasia-hw2/database/rewards.db
```

## Scope

Gift-card list, selected details, add, update and confirmed delete are
implemented. ACCOUNT is seeded for HW4 only. Login, sessions, authorization,
customer administration and redemption remain out of scope. The original
login.php is preserved unchanged; start HW3 at card-list.php. No page in the
HW3 workflow depends on login. There is no JavaScript or front-end framework.

## Requirements used

- Original AirAsia requirements: exact ACCOUNT/GIFTCARD columns and types.
- AirAsia_HW3_Instructions.docx: HW3 behavior, deliverables and testing.
- Attached AirAsia_HW3_SAD_Requirements_and_Business_Rules.docx: FR, BR, DR,
  UC and acceptance-test traceability (populated HW3 specification).
- Existing wireframes and pages: visual layout; they do not expand scope.

The HW3 instructions override the old update/delete flow: updates return to
selected details; deletion requires a confirmation screen and affirmative
POST. ACCOUNT.userId is required but has no foreign key to a deferred USER
table. No USER/REDEMPTION tables or image columns were added.

## Files

```text
air-asia/
  README.md
  test-report.md
  ai-use.txt                 # Preserved HW2 history plus HW3 disclosure
  .vscode/settings.json      # Existing editor wrap/indent preferences
  airasia-hw2/               # Existing application, now implementing HW3
    login.php                # Unchanged HW2 prototype; not an HW3 entry point
    card-list.php
    card-details.php
    card-add.php
    card-update.php
    card-delete.php
    includes/
      header.php
      nav.php
      footer.php
      db.php
      validation.php
    database/
      schema.sql
      seed.sql
      rewards.db
    tests/
      validation-tests.php
    css/styles.css
    images/gift-card.png
```

## Run in GitHub Codespaces

1. Open this repository, select **Code > Codespaces**, and create/open a
   Codespace on main. In an existing Codespace run `git pull --ff-only`
   first; commit your own local edits before pulling if needed.
2. From the repository root, check the tools:

   ```bash
   php -v
   php -r 'var_export(PDO::getAvailableDrivers());'
   sqlite3 --version
   ```

   PHP must be version 8 or newer, and the PDO output must include `sqlite`.
   If these tools are missing in the standard Debian/Ubuntu Codespace:

   ```bash
   sudo apt-get update
   sudo apt-get install -y php-cli php-sqlite3 sqlite3
   ```

3. The committed database already contains 20 gift cards and five accounts.
   To create it if missing, or safely rerun setup:

   ```bash
   sqlite3 airasia-hw2/database/rewards.db < airasia-hw2/database/schema.sql
   sqlite3 airasia-hw2/database/rewards.db < airasia-hw2/database/seed.sql
   sqlite3 airasia-hw2/database/rewards.db 'SELECT count(*) FROM GIFTCARD;'
   sqlite3 airasia-hw2/database/rewards.db 'SELECT count(*) FROM ACCOUNT;'
   ```

   `CREATE TABLE IF NOT EXISTS` preserves tables. Seed inserts use fixed
   IDs with `ON CONFLICT(primaryKey) DO NOTHING`; reruns do not duplicate or
   overwrite existing rows. Deliberately rerunning the seed will restore a
   deleted seed ID if it is still vacant. These are demonstration records,
   not real customers or official rewards pricing. The application does
   not seed automatically when a page is opened. Back up useful data before
   any intentional reset; setup does not drop tables or delete records.

4. Start the development server from the repository root:

   ```bash
   php -S 0.0.0.0:8000 -t airasia-hw2
   ```

5. Open port **8000** in the Codespaces **Ports** tab, keep visibility
   **Private**, and append `/card-list.php` to the forwarded URL. The test
   harness is `/tests/validation-tests.php`. There is no index page; a
   request for `/` alone is not the HW3 entry point.

Stop the server with Ctrl+C. PHP's server is for development. GitHub Pages
cannot run this PHP/SQLite application. The directory containing rewards.db
must be writable by PHP so SQLite can create its transaction journal.

## Validation and data decisions

- Existing card types are **Retail, Dining, Entertainment, Travel**. This is
  a documented application choice, not an original-source enumeration.
  `CARD_TYPES` in includes/validation.php drives both forms and validation;
  seed rows and the harness use the same list.
- Names are trimmed, required and limited to 100 Unicode characters. Types
  are required and allowed. Values must be finite and greater than zero.
  The numeric form uses `step="any"` because the business rule does not
  require two decimal places; currency display uses two decimal places.
- Points and IDs use positive decimal integers within PHP's integer range.
  Fractions, signs, exponent notation, arrays and overflowing integers are
  rejected. IDs must exist before reading, updating or deleting a card.
- cardId is database-generated. Add has no ID input; Update shows the ID as
  read-only text. A validated query-string ID identifies the selected record.
- Database/form output uses htmlspecialchars through `e()`. SQL receiving
  input uses prepared statements with bound parameters. Invalid forms get
  helpful field errors and retain safe text/number entries; invalid types
  are not added to the allowed dropdown list.
- Changes use POST and successful writes use a 303 redirect. Invalid input
  gets 422, malformed IDs get 400, nonexistent cards get 404, and database
  failures show a friendly 503 without raw SQL errors in the page.
- Every card uses the existing local gift-card image as a generic fallback;
  no database image field is required. Shared includes retain the existing
  Air Asia styling and offer List and Add navigation without HW4 controls.

## Use Case Traceability

Paths below are relative to airasia-hw2/. All five use cases operate on
GIFTCARD; ACCOUNT has no interface in HW3.

| Use case | SAD | PHP page(s) | Database table | Test evidence |
| --- | --- | --- | --- | --- |
| View a List of Gift Cards | UC-01 / FR-01 | card-list.php | GIFTCARD | T02 rendered 20 saved cards and checked local assets and selected detail links through PHP CGI. |
| View Details of One Gift Card | UC-02 / FR-02 | card-details.php | GIFTCARD | T03 rendered all 20 seeded IDs and verified selected attributes and links; T08 rejected invalid/unknown IDs. |
| Add a Gift Card | UC-03 / FR-03 | card-add.php | GIFTCARD | T04 submitted valid POST data, checked the inserted row and list redirect; T07 rejected altered input. |
| Update a Gift Card | UC-04 / FR-04 | card-update.php, card-details.php | GIFTCARD | T05 verified prefill, updated four values and checked the selected details redirect and saved record. |
| Delete a Gift Card | UC-05 / FR-05 | card-delete.php, card-list.php | GIFTCARD | T06 proved GET/Cancel do not delete, rejected unconfirmed POST, and verified affirmative POST deletes only the selected record. |

## Tests and verification status

From the repository root:

```bash
php airasia-hw2/tests/validation-tests.php
find airasia-hw2 -name '*.php' -exec php -l {} \;
```

The validation harness also displays PASS/FAIL in the browser and never
connects to the database. It currently covers 29 cases. See **test-report.md**
for T01-T08 inputs, expected and actual results, limitations, and manual
Codespaces steps. Automated PHP 8.4.27 CLI/CGI and SQLite tests passed in the
assistant's Linux workspace on November 12, 2026 using an isolated copy.
The committed database retains 20 gift cards and five accounts.

**Student-reported Codespaces check (November 12, 2026):** Cody confirmed
that the website launched, active pages could be used, and a new gift card
could be added, updated and deleted. This confirms the basic manual CRUD
workflow; the assistant did not observe that browser session.

**Still awaiting specific manual results:** database row counts after the
test, the browser validation harness, invalid-input/ID handling, Delete
Cancel behavior, and detailed image/responsive-layout checks. See
test-report.md. Earlier automated CGI tests passed independently; the
assistant's local Chromium attempt was blocked by socket restrictions.

## Instructor review and submission

The source, populated database, tests and documentation are in this
repository. Read ai-use.txt for preserved prompts/history and the HW3 AI
contribution record. Review and understand the code, run the Codespaces
checks in test-report.md, and record your own actual browser results before
submitting the repository link in Canvas. No Canvas submission has been
performed by the assistant.
