# Airlight

WordPress development environment using Lando with the Air-Light theme.

## Setup Steps

1. Created initial files:
   - `.gitignore`
   - `.lando/php.ini`
   - `.lando.yml`

2. Downloaded WordPress core:
   ```bash
   lando wp core download
   ```

3. Downloaded Air-Light theme to `wp-content/themes/`

4. Installed theme dependencies:
   ```bash
   lando rebuild -y
   ```

5. Started environment and completed setup wizard:
   ```bash
   lando start
   ```
   Use the installation wizard to setup database, username, password.
