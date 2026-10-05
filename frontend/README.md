# TAYO-TECH React frontend

Standalone React + Vite + Tailwind CSS frontend. The existing PHP application and backend remain in the project root and `public/`; this frontend does not replace or edit them.

## Run locally

```sh
cd frontend
npm install
npm run dev
```

Vite serves the React app at `http://localhost:5173`. PHP entry points such as `/login.php` and `/register.php` link to the existing backend and require the PHP application to be served separately. Set `VITE_API_BASE_URL` (see `.env.example`) when the JSON API uses a different origin.

## Data and API integration

- `src/data/homepage.js` contains representative content shaped for homepage components.
- `src/services/homepage.js` contains fetch functions for the documented `GET /api/v1/opportunities` and `GET /api/v1/events` endpoints.
- `OpportunitySection`, `EventsSection`, and `StoriesSection` accept collections as props, so API responses can replace fixture data without changing the section UI.
- Opportunities and events are documented as reserved/501 in `docs/API.md`. Success stories do not have a public API contract yet; add one before replacing those fixtures.

The current API envelope is `{ success, data }`; list endpoints should return an array in `data` for these collection functions. Requests include the session cookie for same-origin deployments.
