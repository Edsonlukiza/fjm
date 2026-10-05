const API_BASE = import.meta.env.VITE_API_BASE_URL || '/api/v1'

async function getCollection(path) {
  const response = await fetch(`${API_BASE}${path}`, { headers: { Accept: 'application/json' }, credentials: 'include' })
  if (!response.ok) throw new Error(`Could not load ${path} (${response.status})`)
  const payload = await response.json()
  return payload.data
}

// The opportunities/events endpoints are reserved in docs/API.md. Stories can be
// wired here when a public stories endpoint is introduced. Components accept
// collections as props so fixture data and API responses share one UI contract.
export const getOpportunities = () => getCollection('/opportunities')
export const getEvents = () => getCollection('/events')
