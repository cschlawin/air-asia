# Air Asia

Air Asia Rewards Redemption System — IS 6465 static HTML/CSS assignment.

## Files

The `airasia-hw2/` directory contains the five requested `.php` pages, `ai-use.txt`, `css/styles.css`, and `images/gift-card.png`. The `.php` files contain HTML only. There is no JavaScript, PHP code, database, or persistence.

## Run in GitHub Codespaces

1. Open this repository on GitHub. Select **Code → Codespaces → Create codespace on main**.
2. In the terminal, run the following from the repository root:

```bash
php -S 0.0.0.0:8000 -t airasia-hw2
```

3. Open port **8000** from the **Ports** tab, keep its visibility **Private**, and append `/login.php` to the forwarded URL.
4. Use any sample username and password. Do not enter real credentials.

If the Codespace does not include the PHP executable, install it as a preview server:

```bash
sudo apt-get update
sudo apt-get install -y php-cli
```

PHP is only the preview server; no PHP programming is required. GitHub Pages does not execute PHP files, so use the Codespaces preview for this assignment. There is intentionally no `index.php`; begin at `login.php`.

## Required workflow

- `login.php` → `card-list.php`
- `card-list.php` → `card-details.php` or `card-add.php`
- `card-details.php` → `card-update.php`
- Add and update forms → `card-list.php`

The six gift cards use sample data. Every card opens the one Amazon sample details page. Add and update submissions navigate only and do not change the list. Login does not authenticate. Native HTML required, numeric, and selection constraints provide basic input validation.

## Create the GitHub repository manually if needed

GitHub repository names cannot contain spaces: use **Air-Asia** and keep **Air Asia** as the website name. Create a blank repository, extract the supplied ZIP, and upload the contents of its `Air-Asia` folder so `README.md` and `airasia-hw2/` are at the repository root. Then open Codespaces as described above.
