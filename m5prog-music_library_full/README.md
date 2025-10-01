# M5PROG Music Library

## Quick start
1. Copy `.env.example` to `.env`.
2. Run `docker compose up`.
3. Go to phpMyAdmin: http://localhost:8805 (user: app, pass: app, db: music_library).
4. Import `sql/seed.sql`.
5. Run `npm install` and `npm run dev`.
6. Open http://localhost to see the app.

Features: Overview, Single detail, Search, SEO links for artist/genre.
