// Replace these fixtures with the matching API service calls in services/homepage.js.
export const features = [
  { icon: 'briefcase', title: 'Career opportunities', description: 'Roles, internships and scholarships', href: '#opportunities' },
  { icon: 'graduation', title: 'Learning & skills', description: 'Training that moves you forward', href: '#learning' },
  { icon: 'lightbulb', title: 'Innovation', description: 'Turn ideas into real impact', href: '#innovation' },
  { icon: 'users', title: 'Mentorship', description: 'Grow with experienced leaders', href: '#mentorship' },
  { icon: 'chart', title: 'Entrepreneurship', description: 'Build and grow your business', href: '#entrepreneurship' },
  { icon: 'calendar', title: 'Events & community', description: 'Meet, share and collaborate', href: '#events' },
  { icon: 'book', title: 'Resources', description: 'Guides, research and toolkits', href: '#resources' },
]

export const opportunities = [
  { id: 'job-1', initials: 'NMB', title: 'Software Developer', organization: 'NMB Bank Plc', location: 'Dar es Salaam', type: 'Full time', category: 'Technology', postedAt: 'New', color: 'orange' },
  { id: 'job-2', initials: 'CRDB', title: 'ICT Support Officer', organization: 'CRDB Bank', location: 'Dar es Salaam', type: 'Full time', category: 'Technology', postedAt: 'New', color: 'blue' },
  { id: 'job-3', initials: 'V', title: 'Data Analyst', organization: 'Vodacom Tanzania', location: 'Dar es Salaam', type: 'Full time', category: 'Data', postedAt: 'Closing soon', color: 'red' },
]

export const events = [
  { id: 'event-1', day: '24', month: 'MAY', year: '2025', title: 'TAYO-TECH Innovation Summit', venue: 'JNICC, Dar es Salaam', format: 'In person', category: 'Summit' },
  { id: 'event-2', day: '28', month: 'MAY', year: '2025', title: 'Web Development Workshop', venue: 'Online', format: 'Virtual', category: 'Workshop' },
]

export const stories = [
  { id: 'story-1', name: 'Brian Mwita', role: 'Software Developer at NMB', quote: 'TAYO-TECH helped me find a job opportunity and connected me with a mentor who guided me to become a better developer.', initials: 'BM' },
  { id: 'story-2', name: 'Amina Juma', role: 'Founder, Amina Designs', quote: 'Through the entrepreneurship program I turned my idea into a registered business in under three months.', initials: 'AJ' },
  { id: 'story-3', name: 'Elias Mrema', role: 'Data Analyst, Vodacom', quote: 'The training courses gave me the certifications I needed to land my first tech role.', initials: 'EM' },
]
