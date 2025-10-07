# RFID Attendance System

An RFID-based attendance tracking system that combines Arduino hardware with a web backend (PHP/MySQL) to register users, read RFID tag scans, and record/classroom attendance.

## Tech Stack

- Arduino + Web (PHP/MySQL)

## Features

- Register users/students with RFID UID and profile details
- Scan RFID tags to mark check-in / check-out timestamps
- Web dashboard to view logs and attendance
- SQL schema for users, cards, and attendance logs

## Project Structure

```text
  __MACOSX/
    ._rfid-attendance-project
    rfid-attendance-project/
      ._.git
      ._.gitattributes
      ._README.md
      .git/
        ._COMMIT_EDITMSG
        ._FETCH_HEAD
        ._HEAD
        ._branches
        ._config
        ._description
        ._hooks
        ._index
        ._info
        ._logs
        ._objects
        ._refs
        hooks/
          ._applypatch-msg.sample
          ._commit-msg.sample
          ._fsmonitor-watchman.sample
          ._post-update.sample
          ._pre-applypatch.sample
          ._pre-commit.sample
          ._pre-merge-commit.sample
          ._pre-push.sample
          ._pre-rebase.sample
          ._pre-receive.sample
          ._prepare-commit-msg.sample
          ._push-to-checkout.sample
          ._sendemail-validate.sample
          ._update.sample
        info/
          ._exclude
        logs/
          ._HEAD
          ._refs
          refs/
            ._heads
            heads/
              ._main
        objects/
          ._1e
          ._54
          ._77
          ._df
          ._info
          ._pack
          1e/
            ._9d4f04ceb24b6d7fd1624915553955cc9d8299
          54/
            ._5128081ba2765bcc3205948060cb45238c00c6
          77/
            ._fa750e6ca0976efd9ba74e1f8a33c1aa2b8a1a
          df/
            ._e0770424b2a19faf507a501ebfc23be8f54e7b
        refs/
          ._heads
          ._tags
          heads/
            ._main
  rfid-attendance-project/
    .gitattributes
    README.md
    .git/
      COMMIT_EDITMSG
      FETCH_HEAD
      HEAD
      config
      description
      index
      branches/
      hooks/
        applypatch-msg.sample
        commit-msg.sample
        fsmonitor-watchman.sample
        post-update.sample
        pre-applypatch.sample
        pre-commit.sample
        pre-merge-commit.sample
        pre-push.sample
        pre-rebase.sample
        pre-receive.sample
        prepare-commit-msg.sample
        push-to-checkout.sample
        sendemail-validate.sample
        update.sample
      info/
        exclude
      logs/
        HEAD
        refs/
          heads/
            main
      objects/
        1e/
          9d4f04ceb24b6d7fd1624915553955cc9d8299
        54/
          5128081ba2765bcc3205948060cb45238c00c6
        77/
          fa750e6ca0976efd9ba74e1f8a33c1aa2b8a1a
        df/
          e0770424b2a19faf507a501ebfc23be8f54e7b
        info/
        pack/
      refs/
        heads/
          main
        tags/
```

## Setup

### 1) Database

- Create a MySQL database and import the provided `.sql` schema (see the `/sql` or root folder).

### 2) Web App (PHP)

- Configure database credentials in `config.php` (create if missing) with host, database, username, and password.

- Serve the PHP files using XAMPP/MAMP/LAMP or PHP's built-in server.

  ```bash
  php -S localhost:8000 -t public
  ```

### 3) Arduino

- Load the Arduino sketch (usually `.ino`) and configure it for your RFID reader (e.g., MFRC522).

## Configuration

Create a `config.php` with `$host`, `$username`, `$password`, `$dbname` variables for DB connection.

## Usage

1. Start the web server and ensure the DB is accessible.
2. Register users and associate their RFID UIDs.
3. Scan a card: the system logs timestamp (check‑in/out) and displays status.
4. View attendance history and export if available.

## Credits / License

AMSA Full Stack Development Class Project
- Brian Bakkala
- Panapitiyage Methru Bandara
- Manraaj Singh
- Aneesh Guda
- Gabriel Hurez-Soler
- James Clayton
- Dylan Zickus
- Michael Domino
- Austin Gavin

