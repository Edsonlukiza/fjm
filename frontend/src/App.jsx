import { useState } from 'react'
import {
  BriefcaseBusiness, CalendarDays, Check, GraduationCap, Lightbulb, MapPin,
  Menu, Search, Users, X, BookOpen, ChartNoAxesColumnIncreasing, Quote, Globe2,
} from 'lucide-react'
import { events, features, opportunities, stories } from './data/homepage.js'
const logoImage = '/assets/img/logo.png'

const iconMap = { briefcase: BriefcaseBusiness, graduation: GraduationCap, lightbulb: Lightbulb, users: Users, chart: ChartNoAxesColumnIncreasing, calendar: CalendarDays, book: BookOpen }
const nav = [
  { label: 'Home', href: '#home' },
  { label: 'Opportunities', href: '#opportunities', items: ['Job board', 'Internships', 'Scholarships', 'My applications'] },
  { label: 'Learning', href: '#learning', items: ['Training & courses', 'Mentorship', 'Resource centre'] },
  { label: 'Innovation', href: '#innovation', items: ['Entrepreneurship hub', 'Innovation hub', 'Investor connect'] },
  { label: 'Community', href: '#events', items: ['Events & webinars', 'Discussion forum', 'Tech communities'] },
  { label: 'About', href: '/about.php' },
]

function Header() {
  const [open, setOpen] = useState(false)
  const [active, setActive] = useState('')
  return <header className="site-header"><div className="container header-inner">
    <a className="brand" href="#home" aria-label="TAYO-TECH home"><img src={logoImage} alt="TAYO-TECH" /></a>
    <nav className={`main-nav ${open ? 'is-open' : ''}`} aria-label="Main navigation">
      {nav.map((item) => <div className="nav-item" key={item.label} onMouseEnter={() => setActive(item.label)} onMouseLeave={() => setActive('')}>
        <a className={`nav-link ${item.label === 'Home' ? 'active' : ''} ${active === item.label && item.items ? 'is-open' : ''}`} href={item.href} onClick={() => setOpen(false)}>{item.label}</a>
        {item.items && <div className={`nav-dropdown ${active === item.label ? 'visible' : ''}`}><div className="dropdown-heading"><span>Explore</span><strong>{item.label}</strong><p>Find a pathway that helps you move forward.</p></div><div className="dropdown-links">{item.items.map((sub) => <a href={item.href} key={sub} onClick={() => setOpen(false)}>{sub}</a>)}</div></div>}
      </div>)}
    </nav>
    <div className="header-actions"><a className="login-link" href="/login.php">Log in</a><a className="button button-lime button-small" href="/register.php">Create account</a><button className="menu-toggle" aria-label={open ? 'Close menu' : 'Open menu'} onClick={() => setOpen(!open)}>{open ? <X /> : <Menu />}</button></div>
  </div></header>
}

function Hero() {
  return <><section className="hero" id="home"><div className="container hero-inner"><div className="hero-copy">
    <h1>Build your future<br />in <span>technology.</span></h1>
    <p>A national platform connecting Tanzanian youth to careers, learning, entrepreneurship and the people who can help make it happen.</p>
    <div className="hero-actions"><a className="button button-lime" href="/register.php">Join the community</a><a className="hero-text-link" href="#opportunities">Explore opportunities</a></div>
    <div className="hero-proof"><div className="proof-avatars"><span>JM</span><span>AN</span><span>BM</span><b>+</b></div><p><strong>One community.</strong><br />Many ways to move forward.</p></div>
  </div><div className="hero-side-note"><span>01 / 04</span><span className="side-line" /><span>Connect · Learn · Innovate</span></div></div>
    <div className="hero-bottom container"><span>Opening doors for the next generation</span><span className="hero-location"><MapPin size={14} /> Tanzania, East Africa</span></div>
  </section><FeatureMarquee /></>
}

function FeatureMarquee() {
  const items = [...features, ...features]
  return <section className="marquee" aria-label="Explore TAYO-TECH"><div className="marquee-label"><span>Explore</span><span>01 — 07</span></div><div className="marquee-window"><div className="marquee-track">{items.map((feature, i) => { const Icon = iconMap[feature.icon]; return <a className="marquee-item" href={feature.href} key={`${feature.title}-${i}`}><span className="marquee-icon"><Icon size={18} strokeWidth={1.7} /></span><span><strong>{feature.title}</strong><small>{feature.description}</small></span></a> })}</div></div></section>
}

function SectionTitle({ eyebrow, title, text, action = 'View all', href = '#' }) {
  return <div className="section-heading"><div><div className="eyebrow"><span className="eyebrow-dot" />{eyebrow}</div><h2>{title}</h2>{text && <p>{text}</p>}</div><a className="text-action" href={href}>{action}</a></div>
}

function OpportunitySection({ items = opportunities }) {
  return <section className="section opportunities-section" id="opportunities"><div className="container"><SectionTitle eyebrow="Find your next step" title="Latest opportunities" text="Discover roles, placements and funding from organisations across Tanzania." href="#opportunities" />
    <div className="opportunity-toolbar"><div className="filter-tabs"><button className="filter-tab selected">All opportunities <span>{items.length}</span></button><button className="filter-tab">Jobs</button><button className="filter-tab">Internships</button><button className="filter-tab">Scholarships</button></div><a className="search-opportunities" href="#opportunities"><Search size={16} /> Search opportunities</a></div>
    <div className="opportunity-list">{items.map((item) => <a className="opportunity-row" href="#opportunities" key={item.id}><span className={`org-mark ${item.color}`}>{item.initials}</span><span className="opportunity-main"><span className="opportunity-title">{item.title}</span><span className="opportunity-org">{item.organization}</span></span><span className="opportunity-detail"><MapPin size={15} />{item.location}</span><span className="opportunity-detail"><BriefcaseBusiness size={15} />{item.type}</span><span className={`status-label ${item.postedAt === 'New' ? 'is-new' : ''}`}><i />{item.postedAt}</span></a>)}</div>
    <div className="opportunity-footer"><span>Showing <strong>{items.length} opportunities</strong></span><a className="text-action" href="#opportunities">Browse all opportunities</a></div>
  </div></section>
}

function EventsSection({ items = events }) {
  return <section className="section events-section" id="events"><div className="container"><SectionTitle eyebrow="Make connections" title="Upcoming events" text="Learn something new, meet your community and share what you are building." href="#events" /><div className="events-grid">{items.map((event) => <article className="event-card" key={event.id}><div className="event-art"><span className="event-category">{event.category}</span><div className="event-art-mark"><span>TT</span><i /><i /><i /></div><span className="event-year">{event.year} · TANZANIA</span></div><div className="event-content"><div className="event-date"><strong>{event.day}</strong><span>{event.month}</span></div><div className="event-info"><h3>{event.title}</h3><p><MapPin size={15} />{event.venue}</p><span className="event-format"><Globe2 size={14} />{event.format}</span></div></div></article>)}</div></div></section>
}

function StoriesSection({ items = stories }) {
  const [index, setIndex] = useState(0)
  const story = items[index % items.length]
  if (!story) return null
  return <section className="stories-section" id="stories"><div className="container stories-inner"><div className="stories-intro"><div className="eyebrow light"><span className="eyebrow-dot" />Real people, real progress</div><h2>Opportunity can<br />change a <span>trajectory.</span></h2><p>Young people across Tanzania are putting their skills to work and building what comes next.</p><a className="story-all" href="#stories">Read their stories</a><div className="story-controls"><span>0{index + 1} <i /> 0{items.length}</span><button aria-label="Previous story" onClick={() => setIndex((index - 1 + items.length) % items.length)}>Previous</button><button aria-label="Next story" onClick={() => setIndex((index + 1) % items.length)}>Next</button></div></div><article className="story-feature"><Quote className="quote-mark" size={42} /><blockquote>{story.quote}</blockquote><div className="story-person"><span className="story-avatar">{story.initials}</span><span><strong>{story.name}</strong><small>{story.role}</small></span><Check className="verified" size={18} /></div><div className="story-progress"><span style={{ width: `${((index + 1) / items.length) * 100}%` }} /></div></article></div></section>
}

function QuickAccess() {
  const links = [['Update profile', 'Complete your member profile'], ['My applications', 'Follow your applications'], ['My learning', 'Continue a course'], ['My messages', 'Connect with your network']]
  return <section className="quick-section" id="learning"><div className="container quick-inner"><div className="quick-copy"><div className="eyebrow"><span className="eyebrow-dot" />Your member space</div><h2>Everything you need,<br />in one place.</h2><p>Pick up where you left off and keep your next step moving.</p><a className="button button-dark" href="/login.php">Go to your dashboard</a></div><div className="quick-links">{links.map(([title, desc], i) => <a href="/login.php" key={title}><span className="quick-number">0{i + 1}</span><span><strong>{title}</strong><small>{desc}</small></span></a>)}</div></div></section>
}

function Footer() {
  return <footer className="footer"><div className="container"><div className="footer-top"><a className="brand footer-brand" href="#home"><img src={logoImage} alt="TAYO-TECH" /><span>Tanzania Youth-Tech Forum</span></a><div className="footer-nav"><a href="#opportunities">Opportunities</a><a href="#events">Events</a><a href="/about.php">About TAYO-TECH</a><a href="#home">Contact</a></div><a className="footer-top-link" href="#home">Back to top</a></div><div className="footer-bottom"><span>© {new Date().getFullYear()} TAYO-TECH. All rights reserved.</span><span>Built for Tanzania’s next generation.</span></div></div></footer>
}

export default function App() {
  return <><Header /><main><Hero /><OpportunitySection /><EventsSection /><StoriesSection /><QuickAccess /></main><Footer /></>
}
