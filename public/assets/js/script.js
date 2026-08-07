document.addEventListener('DOMContentLoaded', () => {

  /* ---------- Mobile nav toggle ---------- */
  const navToggle = document.getElementById('navToggle');
  navToggle?.addEventListener('click', () => {
    document.body.classList.toggle('nav-open');
  });

  /* ---------- Dropdown interaction ---------- */
  const dropdownItems = document.querySelectorAll('.nav-item.has-dropdown');
  dropdownItems.forEach((item) => {
    const trigger = item.querySelector('.nav-link');
    trigger?.addEventListener('click', (event) => {
      if (window.innerWidth <= 900) {
        event.preventDefault();
        const isOpen = item.classList.contains('open');
        dropdownItems.forEach((entry) => entry.classList.remove('open'));
        if (!isOpen) item.classList.add('open');
      }
    });
  });

  document.addEventListener('click', (event) => {
    if (!event.target.closest('.nav-item.has-dropdown')) {
      dropdownItems.forEach((item) => item.classList.remove('open'));
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 900) {
      dropdownItems.forEach((item) => item.classList.remove('open'));
    }
  });

  /* ---------- Dark mode toggle ---------- */
  const themeToggle = document.getElementById('themeToggle');
  const savedTheme = localStorage.getItem('tayo-theme');
  if (savedTheme === 'dark') document.body.classList.add('dark');

  themeToggle?.addEventListener('click', () => {
    document.body.classList.toggle('dark');
    localStorage.setItem('tayo-theme', document.body.classList.contains('dark') ? 'dark' : 'light');
  });

  /* ---------- Success story slider ---------- */
  const slider = document.getElementById('storySlider');
  if (slider) {
    const slides = slider.querySelectorAll('.story-slide');
    const dots = slider.querySelectorAll('.dot');
    let current = 0;
    let timer;

    const goTo = (index) => {
      slides[current].classList.remove('active');
      dots[current].classList.remove('active');
      current = (index + slides.length) % slides.length;
      slides[current].classList.add('active');
      dots[current].classList.add('active');
    };

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        goTo(i);
        resetTimer();
      });
    });

    const startTimer = () => { timer = setInterval(() => goTo(current + 1), 5000); };
    const resetTimer = () => { clearInterval(timer); startTimer(); };
    startTimer();
  }

  /* ---------- Smooth close of mobile nav on link click ---------- */
  document.querySelectorAll('.main-nav .nav-link').forEach(link => {
    link.addEventListener('click', () => document.body.classList.remove('nav-open'));
  });

});