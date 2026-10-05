# TAYO-TECH frontend

React frontend for the TAYO-TECH Tanzania Youth-Tech Forum homepage. It is a separate Vite app so the PHP backend can stay in place while the frontend is developed. Backend code and PHP pages are outside this project.

## Technology

- React with JSX for the page and interactive sections
- Vite for local development and production builds
- Tailwind CSS v4 is installed through the Vite plugin and imported in `src/styles.css`
- Lucide React for interface icons
- Custom CSS for the responsive page layout, visual system, and motion

## Requirements

- Node.js 20.19+ or 22.12+ (current Vite releases require a recent Node version)
- npm
- PHP server for the existing login, registration, and API routes

## Start the frontend

From this folder:

```sh
npm install
npm run dev
```

Vite prints the local URL, normally `http://localhost:5173`. The React page can run by itself. Links such as `/login.php`, `/register.php`, and `/about.php` point to the existing PHP application, so those destinations require the backend web server to be available on the same origin. Configure a Vite proxy or deploy the frontend and PHP application under a shared origin when developing those flows together.

Build and serve a production preview with:

```sh
npm run build
npm run preview
```

The production build is written to `dist/`.

## Configuration

Copy `.env.example` to `.env.local` to set the API base URL:

```dotenv
VITE_API_BASE_URL=/api/v1
```

The default is `/api/v1`, matching the PHP API when both applications are served from the same origin. For a separately hosted API, use its full base URL. Cross-origin session authentication also requires the backend's CORS and cookie settings to allow the frontend origin.

## Project structure

```text
frontend/
├── index.html                   # Vite HTML entry point and document metadata
├── public/assets/img/            # Static logo, hero and leadership photographs
├── src/
│   ├── main.jsx                  # React mount point
│   ├── App.jsx                   # Homepage sections and navigation
│   ├── styles.css                # Tailwind import, tokens, page and responsive styles
│   ├── data/homepage.js          # Sample homepage collections
│   └── services/homepage.js      # HTTP functions for homepage endpoints
├── .env.example
├── package.json
└── vite.config.js
```

### Main page sections

- Sticky desktop and mobile navigation with connected hover dropdowns
- Hero with the `hero-section.png` background and TAYO-TECH leadership photos
- Moving feature band; pauses while hovered and respects reduced-motion preferences
- Latest opportunities list
- Upcoming events
- Success story carousel controls
- Member quick-access links and footer

The page uses semantic links and buttons, accessible labels for icon-only controls, responsive layouts, and a reduced-motion stylesheet rule.

## Color palette

The interface uses the four supplied brand colors. Translucent tints of these colors are used for borders, overlays, and hover backgrounds.

| Color | Hex | Use |
|---|---|---|
| Deep blue | `#0E1B4F` | Header, footer, hero overlay, dark sections, primary text |
| Green | `#00B551` | Primary actions, active navigation, status accents, highlights |
| Blue | `#2E8BC0` | Secondary accents, event panels, organization marks |
| White | `#FFFFFF` | Main surfaces, cards, and text on dark areas |

The CSS variables are defined near the end of `src/styles.css` as `--navy`, `--navy2`, `--ink`, `--muted`, `--line`, `--lime`, and `--paper`. Their values are mapped to the brand palette so existing component styles share the same tokens.

## Images and static files

Vite serves files in `public/` from the site root. For example, `public/assets/img/hero-section.png` is referenced as `/assets/img/hero-section.png`.

The frontend currently has its own copies of the logo, hero image, and leadership photos. If those assets are changed in the PHP `public/assets/img/` folder, copy the updated versions into `frontend/public/assets/img/` as well. This keeps the backend folder independent from Vite's build output.

## Homepage data and API integration

`src/data/homepage.js` supplies representative content for the initial UI. Its records are shaped for the homepage sections:

```js
// Opportunity
{ id, initials, title, organization, location, type, category, postedAt, color }

// Event
{ id, day, month, year, title, venue, format, category }

// Success story
{ id, name, role, quote, initials }
```

`src/services/homepage.js` provides `getOpportunities()` and `getEvents()`. Each calls the corresponding GET route, sends `Accept: application/json`, includes credentials, and returns the response's `data` value. The expected API envelope is `{ success, data }`.

The components in `App.jsx` accept their collection as a prop, with the local fixtures as defaults. To connect an endpoint, load the data in `App` and pass the response into the section. The opportunities and events routes are documented in `docs/API.md`, but are currently reserved and may return `501` until the backend implements them. Success stories do not yet have a documented public endpoint; agree on that API contract before wiring them.

## Backend boundaries

The frontend does not implement authentication, persistence, or business rules. Login, registration, and About links target the existing PHP pages. API calls use the PHP API contract documented in `docs/API.md`. Keep server-side validation, session handling, CSRF checks, and authorization in the backend.

## Useful scripts

| Command | Purpose |
|---|---|
| `npm run dev` | Start Vite development server |
| `npm run build` | Create production files in `dist/` |
| `npm run preview` | Serve the production build locally |
