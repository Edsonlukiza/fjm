<?php
/**
 * TAYO-TECH — Tanzania Youth-Tech Forum
 * Landing page. Content lives in PHP arrays below so it's easy to swap
 * for real data (DB / API) later without touching the markup structure.
 */

// ---------- Site data ----------

$nav = [
    [
        'label' => 'Home',
        'href' => '#',
        'active' => true,
        'description' => 'Welcome to the main hub for youth opportunities and innovation.',
        'groups' => []
    ],
    [
        'label' => 'Opportunities',
        'href' => '#opportunities',
        'description' => 'Career growth, employment, and funded positions for young professionals.',
        'groups' => [
            [
                'title' => 'Explore roles',
                'items' => [
                    ['label' => 'Job Board', 'href' => '#', 'description' => 'Browse all available positions'],
                    ['label' => 'Internship Portal', 'href' => '#', 'description' => 'Graduate and student placements'],
                    ['label' => 'Scholarships', 'href' => '#', 'description' => 'Local and international grants'],
                    ['label' => 'My Applications', 'href' => '#', 'description' => 'Track saved and submitted applications']
                ]
            ]
        ]
    ],
    [
        'label' => 'Learning & Skills',
        'href' => '#training',
        'description' => 'Capacity building, education, and professional development resources.',
        'groups' => [
            [
                'title' => 'Build your future',
                'items' => [
                    ['label' => 'Training & Courses', 'href' => '#', 'description' => 'Digital skills and certifications'],
                    ['label' => 'Mentorship Program', 'href' => '#', 'description' => 'Connect with industry leaders'],
                    ['label' => 'Resource Center', 'href' => '#', 'description' => 'Guides, templates and toolkits']
                ]
            ]
        ]
    ],
    [
        'label' => 'Innovation & Business',
        'href' => '#entrepreneurship',
        'description' => 'Startup support, entrepreneurship, and venture creation pathways.',
        'groups' => [
            [
                'title' => 'Launch and grow',
                'items' => [
                    ['label' => 'Entrepreneurship Hub', 'href' => '#', 'description' => 'Pitching and incubator support'],
                    ['label' => 'Business Registration', 'href' => '#', 'description' => 'Guidance and startup tools'],
                    ['label' => 'Innovation Hub', 'href' => '#', 'description' => 'Hackathons and showcase projects'],
                    ['label' => 'Investor Connect', 'href' => '#', 'description' => 'Meet angel investors and VCs']
                ]
            ]
        ]
    ],
    [
        'label' => 'Community & Events',
        'href' => '#events',
        'description' => 'Engagement, discussion, and networking opportunities across the ecosystem.',
        'groups' => [
            [
                'title' => 'Join the movement',
                'items' => [
                    ['label' => 'Events & Webinars', 'href' => '#', 'description' => 'Upcoming meetups and workshops'],
                    ['label' => 'Discussion Forum', 'href' => '#', 'description' => 'Ask questions and share ideas'],
                    ['label' => 'Tech Communities', 'href' => '#', 'description' => 'Regional and interest-based groups']
                ]
            ]
        ]
    ],
    [
        'label' => 'About Us',
        'href' => 'about.php',
        'description' => 'Mission, vision, and partners for the TAYO-TECH community.',
        'groups' => [
            [
                'title' => 'Support and information',
                'items' => [
                    ['label' => 'About Us', 'href' => 'about.php', 'description' => 'Mission, vision, and partners'],
                    ['label' => 'Government Services', 'href' => '#', 'description' => 'Integrated youth technology initiatives'],
                    ['label' => 'Reports & Analytics', 'href' => '#', 'description' => 'Public impact and statistics'],
                    ['label' => 'Contact Us & Support', 'href' => '#', 'description' => 'Help desk and inquiries']
                ]
            ]
        ]
    ]
];

$features = [
    ['icon' => 'briefcase', 'color' => 'green',  'title' => 'Job Opportunities',   'desc' => 'Find jobs and apply to top companies in Tanzania.'],
    ['icon' => 'cap',       'color' => 'blue',   'title' => 'Training & Courses',  'desc' => 'Access quality training and certifications online.'],
    ['icon' => 'bulb',      'color' => 'purple', 'title' => 'Innovation Hub',      'desc' => 'Showcase your ideas and participate in competitions.'],
    ['icon' => 'people',    'color' => 'orange', 'title' => 'Mentorship',          'desc' => 'Connect with mentors and grow your career.'],
    ['icon' => 'chart',     'color' => 'teal',   'title' => 'Entrepreneurship',    'desc' => 'Start, grow and fund your business ideas.'],
    ['icon' => 'calendar',  'color' => 'red',    'title' => 'Events',              'desc' => 'Join workshops, seminars and tech events.'],
    ['icon' => 'book',      'color' => 'blue',   'title' => 'Resources',           'desc' => 'Access materials, research and digital resources.'],
];

$opportunities = [
    ['logo' => 'NMB',  'logoBg' => '#f97316', 'title' => 'Software Developer', 'company' => 'NMB Bank Plc', 'location' => 'Dar es Salaam · Full Time', 'badge' => 'New'],
    ['logo' => 'CRDB', 'logoBg' => '#1d4ed8', 'title' => 'ICT Support Officer', 'company' => 'CRDB Bank',   'location' => 'Dar es Salaam · Full Time', 'badge' => 'New'],
    ['logo' => 'VF',   'logoBg' => '#e11d48', 'title' => 'Data Analyst',        'company' => 'Vodacom Tanzania', 'location' => 'Dar es Salaam · Full Time', 'badge' => ''],
];

$events = [
    ['title' => 'TAYO-TECH Innovation Summit 2025', 'date' => '24 May 2025', 'venue' => 'JNICC, Dar es Salaam', 'cta' => 'Register Now'],
    ['title' => 'Web Development Workshop',         'date' => '28 May 2025', 'venue' => 'Online',               'cta' => 'Register Now'],
];

$stories = [
    ['name' => 'Brian Mwita', 'role' => 'Software Developer at NMB', 'quote' => 'TAYO-TECH helped me find a job opportunity and also connected me with a mentor who guided me to become a better developer.'],
    ['name' => 'Amina Juma',  'role' => 'Founder, Amina Designs',     'quote' => 'Through the entrepreneurship program I turned my idea into a registered business in under three months.'],
    ['name' => 'Elias Mrema', 'role' => 'Data Analyst, Vodacom',      'quote' => 'The training courses gave me the certifications I needed to land my first tech role.'],
];

$quickAccess = ['Update Profile', 'My Applications', 'My Trainings', 'My Messages', 'My Bookmarks'];

// Simple SVG icon library so we don't depend on any external icon font.
function icon(string $name, string $class = ''): string {
    $icons = [
        'briefcase' => '<path d="M4 7h16v11H4z"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><path d="M4 12h16"/>',
        'cap'       => '<path d="M12 3 2 8l10 5 10-5-10-5z"/><path d="M6 10.5V16c0 1.5 3 3 6 3s6-1.5 6-3v-5.5"/>',
        'bulb'      => '<path d="M9 18h6"/><path d="M10 21h4"/><path d="M12 3a6 6 0 0 0-3.6 10.8c.5.4.8 1 .8 1.7V16h5.6v-.5c0-.7.3-1.3.8-1.7A6 6 0 0 0 12 3z"/>',
        'people'    => '<circle cx="9" cy="8" r="3"/><path d="M2 20c0-3.3 3.1-6 7-6s7 2.7 7 6"/><circle cx="17" cy="9" r="2.5"/><path d="M17 13c2.6 0 5 1.8 5 4.3"/>',
        'chart'     => '<path d="M4 20V10"/><path d="M11 20V4"/><path d="M18 20v-7"/><path d="M2 20h20"/>',
        'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M3 10h18"/>',
        'book'      => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15.5A2.5 2.5 0 0 1 17.5 21H4z"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20"/>',
        'building'  => '<rect x="4" y="3" width="9" height="18"/><rect x="15" y="9" width="5" height="12"/><path d="M7 7h3M7 11h3M7 15h3"/>',
        'mentor'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"/>',
        'trophy'    => '<path d="M8 4h8v5a4 4 0 0 1-8 0V4z"/><path d="M6 4H4v2a4 4 0 0 0 4 4"/><path d="M18 4h2v2a4 4 0 0 1-4 4"/><path d="M12 13v3"/><path d="M9 20h6"/><path d="M10 16h4l1 4H9z"/>',
        'search'    => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
        'moon'      => '<path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/>',
        'arrow'     => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
        'chevron'   => '<path d="m6 9 6 6 6-6"/>',
        'menu'      => '<path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/>',
        'close'     => '<path d="M18 6 6 18"/><path d="M6 6l12 12"/>',
    ];
    $body = $icons[$name] ?? '';
    return "<svg class=\"icon {$class}\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">{$body}</svg>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TAYO-TECH | Tanzania Youth-Tech Forum</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ===== Header ===== -->
<header class="site-header">
  <div class="container header-inner">
    <a href="#" class="brand" aria-label="TAYO-TECH home">
      <img src="assets/img/logo.png" alt="TAYO-TECH logo" class="brand-logo">
    </a>

    <nav class="main-nav" id="mainNav">
      <?php foreach ($nav as $item): ?>
        <?php $hasDropdown = !empty($item['groups']); ?>
        <div class="nav-item<?= $hasDropdown ? ' has-dropdown' : '' ?>">
          <a href="<?= $item['href'] ?>" class="nav-link<?= !empty($item['active']) ? ' active' : '' ?>">
            <?= $item['label'] ?>
            <?php if ($hasDropdown): ?><?= icon('chevron', 'chevron-mini') ?><?php endif; ?>
          </a>

          <?php if ($hasDropdown): ?>
            <div class="dropdown-menu">
              <div class="dropdown-panel">
                <div class="dropdown-copy">
                  <p class="dropdown-kicker">Explore</p>
                  <h3><?= $item['label'] ?></h3>
                  <p><?= $item['description'] ?></p>
                </div>
                <div class="dropdown-groups">
                  <?php foreach ($item['groups'] as $group): ?>
                    <div class="dropdown-group">
                      <h4><?= $group['title'] ?></h4>
                      <ul>
                        <?php foreach ($group['items'] as $link): ?>
                          <li>
                            <a href="<?= $link['href'] ?>">
                              <span class="menu-title"><?= $link['label'] ?></span>
                              <?php if (!empty($link['description'])): ?><span class="menu-desc"><?= $link['description'] ?></span><?php endif; ?>
                            </a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </nav>

    <div class="header-actions">
      <button class="icon-btn" aria-label="Search"><?= icon('search') ?></button>
      <button class="icon-btn" id="themeToggle" aria-label="Toggle dark mode"><?= icon('moon') ?></button>
      <a href="login.php" class="btn btn-outline">Login</a>
      <a href="register.php" class="btn btn-primary">Register</a>
      <button class="icon-btn nav-toggle" id="navToggle" aria-label="Menu"><?= icon('menu') ?></button>
    </div>
  </div>
</header>

<!-- ===== Hero ===== -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="container hero-inner">
    <div class="hero-copy">
      <span class="hero-pill">Empowering Tanzanian Youth Through Technology</span>
      <h1>Connect. Learn. Innovate.<br><span class="accent">Succeed Together.</span></h1>
      <p>TAYO-TECH is a national digital platform that connects Tanzanian youth with opportunities in jobs, entrepreneurship, training, mentorship and innovation.</p>
      <div class="hero-cta">
        <a href="#register" class="btn btn-primary btn-lg">Join TAYO-TECH Today <?= icon('arrow') ?></a>
        <a href="#opportunities" class="btn btn-ghost btn-lg">Explore Opportunities <?= icon('arrow') ?></a>
      </div>
    </div>
  </div>

  <!-- Moving feature strip -->
  <div class="feature-strip" aria-label="Explore TAYO-TECH opportunities">
    <div class="container">
      <div class="feature-marquee">
        <div class="feature-track">
          <?php foreach ($features as $f): ?>
            <a href="#<?= strtolower(str_replace([' ', '&'], ['-', ''], $f['title'])) ?>" class="feature-card">
              <span class="feature-icon icon-<?= $f['color'] ?>"><?= icon($f['icon']) ?></span>
              <span class="feature-copy"><span class="feature-title"><?= $f['title'] ?></span><span class="feature-desc"><?= $f['desc'] ?></span></span>
            </a>
          <?php endforeach; ?>
          <?php foreach ($features as $f): ?>
            <a href="#<?= strtolower(str_replace([' ', '&'], ['-', ''], $f['title'])) ?>" class="feature-card" aria-hidden="true" tabindex="-1">
              <span class="feature-icon icon-<?= $f['color'] ?>"><?= icon($f['icon']) ?></span>
              <span class="feature-copy"><span class="feature-title"><?= $f['title'] ?></span><span class="feature-desc"><?= $f['desc'] ?></span></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== Main content grid ===== -->
<section class="content-grid">
  <div class="container grid-inner">

    <!-- Latest opportunities -->
    <div class="panel" id="opportunities">
      <div class="panel-head">
        <h2>Latest Opportunities</h2>
        <a href="#" class="view-all">View All <?= icon('arrow') ?></a>
      </div>
      <div class="panel-list">
        <?php foreach ($opportunities as $o): ?>
          <div class="list-row">
            <span class="logo-chip" style="background:<?= $o['logoBg'] ?>"><?= $o['logo'] ?></span>
            <div class="list-copy">
              <span class="list-title"><?= $o['title'] ?></span>
              <span class="list-sub"><?= $o['company'] ?></span>
              <span class="list-meta"><?= $o['location'] ?></span>
            </div>
            <?php if ($o['badge']): ?><span class="badge"><?= $o['badge'] ?></span><?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Upcoming events -->
    <div class="panel" id="events">
      <div class="panel-head">
        <h2>Upcoming Events</h2>
        <a href="#" class="view-all">View All <?= icon('arrow') ?></a>
      </div>
      <div class="panel-list">
        <?php foreach ($events as $e): ?>
          <div class="event-row">
            <div class="event-thumb"></div>
            <div class="list-copy">
              <span class="list-title"><?= $e['title'] ?></span>
              <span class="list-meta"><?= icon('calendar', 'meta-icon') ?> <?= $e['date'] ?></span>
              <span class="list-meta"><?= $e['venue'] ?></span>
              <a href="#" class="btn btn-outline btn-sm"><?= $e['cta'] ?></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Success stories -->
    <div class="panel" id="stories">
      <div class="panel-head">
        <h2>Success Stories</h2>
        <a href="#" class="view-all">View All <?= icon('arrow') ?></a>
      </div>
      <div class="story-slider" id="storySlider">
        <?php foreach ($stories as $i => $st): ?>
          <div class="story-slide <?= $i === 0 ? 'active' : '' ?>">
            <p class="story-quote">&ldquo;<?= $st['quote'] ?>&rdquo;</p>
            <div class="story-person">
              <span class="story-avatar"><?= strtoupper(substr($st['name'], 0, 1)) ?></span>
              <div>
                <span class="story-name"><?= $st['name'] ?></span>
                <span class="story-role"><?= $st['role'] ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="story-dots">
          <?php foreach ($stories as $i => $st): ?>
            <button class="dot <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>" aria-label="Story <?= $i+1 ?>"></button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Quick access -->
    <div class="panel panel-quick">
      <div class="panel-head">
        <h2>Quick Access</h2>
      </div>
      <ul class="quick-list">
        <?php foreach ($quickAccess as $q): ?>
          <li><a href="#"><span class="quick-dot"></span><?= $q ?></a></li>
        <?php endforeach; ?>
      </ul>
      <a href="#" class="btn btn-primary btn-block">Go to Dashboard</a>
    </div>

  </div>
</section>

<!-- ===== Footer ===== -->
<footer class="site-footer">
  <div class="container footer-inner">
    <div class="footer-brand">
      <span class="brand-mark"><?= icon('people') ?></span>
      <span class="brand-name">TAYO-TECH</span>
    </div>
    <p>&copy; <?= date('Y') ?> TAYO-TECH — Tanzania Youth-Tech Forum. All rights reserved.</p>
  </div>
</footer>

<script src="assets/js/script.js"></script>
</body>
</html>
