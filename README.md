# dev-frame

A lightweight Laravel package for hosting, versioning, and live-previewing multiple UI prototypes with a built-in device emulator (Desktop / Tablet / Mobile).

---

## Features

- **Dashboard** — auto-generated list of all UI versions with links to documentation
- **Device Preview** — view each version inside a simulated device frame (Desktop / Tablet / Mobile), switchable in real time
- **Markdown viewer** — renders `README.md` and `DEVLOG.md` from the project root with clean typography
- **Zero Vite config required** — ships a precompiled CSS file; no build step needed in the host app

---

## Requirements

| Dependency | Version |
|---|---|
| PHP | `^8.2` |
| Laravel | `11.x`, `12.x` or `13.x` |

---

## Installation

### 1. Require the package

```bash
composer require mdcznet/dev-frame
```

Laravel's auto-discovery will register `DevFrameServiceProvider` automatically.

### 2. Publish the CSS asset

```bash
php artisan vendor:publish --tag=dev-frame-assets
```

This copies `dist/dev-frame.css` to `public/vendor/dev-frame/dev-frame.css`.

### 3. Done

Open your app in the browser — the dashboard is served at `/`.

---

## Adding a new UI version

1. Create a directory: `resources/views/versions/v2/`
2. Add an entry file: `resources/views/versions/v2/index.blade.php`
3. Open `http://your-app.test/v2` — the new version appears automatically in the dashboard

Versions are auto-detected by scanning `resources/views/versions/` and sorted in descending order (newest first).

---

## Routes registered by the package

| Method | URI | Description |
|---|---|---|
| `GET` | `/` | Dashboard — lists all versions and docs |
| `GET` | `/doc/readme` | Renders `README.md` as HTML |
| `GET` | `/doc/devlog` | Renders `DEVLOG.md` as HTML |
| `GET` | `/{version}` | Device-preview wrapper for the version |
| `GET` | `/{version}?preview=false` | Raw version view without the toolbar |

---

## Customising views

To override the package views, publish them:

```bash
php artisan vendor:publish --tag=dev-frame-views
```

Files are copied to `resources/views/vendor/dev-frame/`. Laravel will use these over the package originals automatically.

---

## Rebuilding the CSS (package development only)

The precompiled CSS in `dist/dev-frame.css` is committed to the repository and **does not need to be rebuilt** when installing the package. It only needs rebuilding when the package UI itself is updated.

```bash
# Requires Node.js + npm
npm install
npm run build:dist
```

---

## License

MIT — see [LICENSE](LICENSE).
