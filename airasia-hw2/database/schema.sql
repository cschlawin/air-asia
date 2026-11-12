-- DATABASE ACCESS NOTES
-- Run Bash commands in the Codespaces terminal, not at the sqlite> prompt.
-- Starting from the repository root (/workspaces/air-asia):
--   cd airasia-hw2/database
--   sqlite3 rewards.db
--
-- At the sqlite> prompt:
--   .tables
--   .schema GIFTCARD
--   .headers on
--   .mode column
--   SELECT * FROM GIFTCARD LIMIT 5;
--   SELECT COUNT(*) AS account_count FROM ACCOUNT;
--   SELECT COUNT(*) AS gift_card_count FROM GIFTCARD;
--   .quit
--
-- Expected application tables: ACCOUNT and GIFTCARD.
-- Seed counts: 5 accounts and 20 gift cards; CRUD may change the counts.
-- After .quit, you are back in Bash in airasia-hw2/database.
-- Optional setup/rerun commands from that folder:
--   sqlite3 rewards.db < schema.sql
--   sqlite3 rewards.db < seed.sql
-- The committed database is already initialized. Rerunning seed.sql
-- preserves existing IDs but can restore deleted sample records.
-- Use sqlite3 only once. The short schema.sql path works only when your
-- current folder contains schema.sql; it does not work at repository root.
-- Return to the repository root from the database folder:
--   cd ../..
-- Or open the database directly from the repository root:
--   sqlite3 airasia-hw2/database/rewards.db
--
-- Exact HW3 entities/columns from the original Air Asia requirements.
-- No USER or REDEMPTION table, and no deferred USER foreign key.
BEGIN;
CREATE TABLE IF NOT EXISTS GIFTCARD (
  cardId INTEGER PRIMARY KEY AUTOINCREMENT,
  cardName VARCHAR(100) NOT NULL
    CHECK (length(trim(cardName)) BETWEEN 1 AND 100),
  cardType VARCHAR(100) NOT NULL
    CHECK (length(trim(cardType)) BETWEEN 1 AND 100),
  cardValue FLOAT(10) NOT NULL
    CHECK (typeof(cardValue) IN ('integer', 'real')
      AND cardValue > 0 AND cardValue <= 1.7976931348623157e308),
  points INT(50) NOT NULL CHECK (typeof(points) = 'integer' AND points > 0)
);
CREATE TABLE IF NOT EXISTS ACCOUNT (
  accountId INTEGER PRIMARY KEY AUTOINCREMENT,
  userId INT(11) NOT NULL CHECK (typeof(userId) = 'integer'),
  accountType VARCHAR(100) NOT NULL
    CHECK (length(trim(accountType)) BETWEEN 1 AND 100),
  points INT(50) NOT NULL CHECK (typeof(points) = 'integer')
);
COMMIT;
