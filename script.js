/* ============================================================
   TIME INC. GROUP — Interactive Behaviors
   Navigation, Scroll Animations, Form Handling
   ============================================================ */

(function () {
  'use strict';

  // --- DOM Elements ---
  const navbar = document.getElementById('navbar');
  const navLinks = document.getElementById('navLinks');
  const navToggle = document.getElementById('navToggle');
  const allNavLinks = document.querySelectorAll('.nav__link');
  const sections = document.querySelectorAll('section[id]');
  const reveals = document.querySelectorAll('.reveal');
  const contactForm = document.getElementById('contactForm');
  const formStatus = document.getElementById('formStatus');
  const heroScroll = document.querySelector('.hero__scroll');

  // --- Navigation: Scroll-based background ---
  let lastScrollY = 0;
  let ticking = false;

  function updateNav() {
    const scrollY = window.scrollY;
    
    if (scrollY > 60) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }

    lastScrollY = scrollY;
    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (!ticking) {
      window.requestAnimationFrame(updateNav);
      ticking = true;
    }
  }, { passive: true });

  // --- Navigation: Active section tracking ---
  function updateActiveLink() {
    let current = 'home';
    const offset = window.innerHeight * 0.35;

    sections.forEach(function (section) {
      const sectionTop = section.offsetTop - offset;
      const sectionBottom = sectionTop + section.offsetHeight;

      if (window.scrollY >= sectionTop && window.scrollY < sectionBottom) {
        current = section.getAttribute('id');
      }
    });

    allNavLinks.forEach(function (link) {
      link.classList.remove('active');
      if (link.getAttribute('data-section') === current) {
        link.classList.add('active');
      }
    });
  }

  window.addEventListener('scroll', function () {
    window.requestAnimationFrame(updateActiveLink);
  }, { passive: true });

  // --- Mobile Menu Toggle ---
  if (navToggle) {
    navToggle.addEventListener('click', function () {
      const isOpen = navLinks.classList.toggle('open');
      navToggle.classList.toggle('active');
      navToggle.setAttribute('aria-expanded', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close menu on link click
    allNavLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        navLinks.classList.remove('open');
        navToggle.classList.remove('active');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });

    // Close menu on outside click
    document.addEventListener('click', function (e) {
      if (navLinks.classList.contains('open') &&
          !navLinks.contains(e.target) &&
          !navToggle.contains(e.target)) {
        navLinks.classList.remove('open');
        navToggle.classList.remove('active');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  }

  // --- Scroll Reveal Animation ---
  if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -60px 0px'
    });

    reveals.forEach(function (el) {
      revealObserver.observe(el);
    });
  } else {
    // Fallback: show everything
    reveals.forEach(function (el) {
      el.classList.add('visible');
    });
  }

  // --- Hero Scroll Button ---
  if (heroScroll) {
    heroScroll.addEventListener('click', function () {
      const newsSection = document.getElementById('news');
      if (newsSection) {
        newsSection.scrollIntoView({ behavior: 'smooth' });
      }
    });
    heroScroll.style.cursor = 'pointer';
  }

  // --- Parallax Effect on Hero Background ---
  const heroBg = document.querySelector('.hero__bg');
  if (heroBg) {
    window.addEventListener('scroll', function () {
      const scrollY = window.scrollY;
      const heroHeight = document.querySelector('.hero').offsetHeight;
      
      if (scrollY < heroHeight) {
        const parallax = scrollY * 0.25;
        heroBg.style.transform = 'translateY(-' + parallax + 'px)';
      }
    }, { passive: true });
  }

  // --- Contact Form Handling ---
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      // Client-side validation
      const name = document.getElementById('contactName');
      const email = document.getElementById('contactEmail');
      const message = document.getElementById('contactMessage');
      let isValid = true;

      // Reset styles
      [name, email, message].forEach(function (field) {
        field.style.borderColor = '';
      });

      if (!name.value.trim()) {
        name.style.borderColor = '#f44336';
        isValid = false;
      }

      if (!email.value.trim() || !isValidEmail(email.value)) {
        email.style.borderColor = '#f44336';
        isValid = false;
      }

      if (!message.value.trim()) {
        message.style.borderColor = '#f44336';
        isValid = false;
      }

      if (!isValid) {
        showFormStatus('Please fill in all required fields correctly.', 'error');
        return;
      }

      // Check if Formspree is configured
      const formAction = contactForm.getAttribute('action');
      if (formAction.includes('YOUR_FORM_ID')) {
        // Fallback: open mailto
        const subject = document.getElementById('contactSubject').value || 'Website Inquiry';
        const body = 'Name: ' + name.value + '\n\nMessage:\n' + message.value;
        window.location.href = 'mailto:hq@time-inc-group.com?subject=' + 
          encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
        showFormStatus('Opening your email client...', 'success');
        return;
      }

      // Formspree submission
      const submitBtn = document.getElementById('contactSubmit');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending...';

      fetch(formAction, {
        method: 'POST',
        body: new FormData(contactForm),
        headers: { 'Accept': 'application/json' }
      })
      .then(function (response) {
        if (response.ok) {
          showFormStatus('Thank you! Your message has been sent successfully.', 'success');
          contactForm.reset();
        } else {
          throw new Error('Form submission failed');
        }
      })
      .catch(function () {
        showFormStatus('Something went wrong. Please try contacting us via email directly.', 'error');
      })
      .finally(function () {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Send Message <span>→</span>';
      });
    });
  }

  function showFormStatus(message, type) {
    if (formStatus) {
      formStatus.textContent = message;
      formStatus.className = 'contact__form-status ' + type;
      
      // Auto-hide after 6 seconds
      setTimeout(function () {
        formStatus.className = 'contact__form-status';
      }, 6000);
    }
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  // --- Smooth scroll for anchor links (fallback) ---
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const targetId = this.getAttribute('href').slice(1);
      const target = document.getElementById(targetId);

      if (target) {
        const navHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height'));
        const targetPosition = target.offsetTop - navHeight;

        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    });
  });

  // --- Initial state ---
  updateNav();
  updateActiveLink();

})();
