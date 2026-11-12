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
