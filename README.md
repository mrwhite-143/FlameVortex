# Flame Approval - Railway Ready

This repository contains a simple approval system (PHP + SQLite) for device keys.

- Admin panel: login.php / approval.php
- API endpoint: api.php?key=DEVICE_KEY
- Create admin: php create_admin.php admin <password>

Default admin password in this package is set to the value you provided, but the password is stored hashed in the database when you run create_admin.php.

Deploy on Railway: push to GitHub, create new project, connect repo, deploy. After deploy run `php create_admin.php` via Railway Shell to seed admin.
