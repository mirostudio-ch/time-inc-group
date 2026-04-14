/* ============================================================
   TIME INC. GROUP — Interactive Behaviors
   Navigation, Scroll Animations, Form Handling (WordPress AJAX)
   ============================================================ */

(function () {
  'use strict';

  // --- DOM Elements ---
  const navbar = document.getElementById('navbar');
  const navLinks = document.getElementById('navLinks');
  const navToggle = document.getElementById('navToggle');
  const allNavLinks = document.querySelectorAll('.nav__link, .nav__links a');
  const sections = document.querySelectorAll('section[id]');
  const reveals = document.querySelectorAll('.reveal');
  const contactForm = document.getElementById('contactForm');
  const formStatus = document.getElementById('formStatus');
  const heroScroll = document.querySelector('.hero__scroll');

  // --- Navigation: Scroll-based background ---
  let ticking = false;

  function updateNav() {
    const scrollY = window.scrollY;
    
    if (scrollY > 60) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }

    ticking = false;
  }

  if (navbar) {
    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(updateNav);
        ticking = true;
      }
    }, { passive: true });
  }

  // --- Navigation: Active section tracking (homepage only) ---
  function updateActiveLink() {
    if (!sections.length) return;

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
      const href = link.getAttribute('href') || '';
      // Match both #section and /#section patterns
      if (href === '#' + current || href.endsWith('/#' + current)) {
        link.classList.add('active');
      }
    });
  }

  if (sections.length) {
    window.addEventListener('scroll', function () {
      window.requestAnimationFrame(updateActiveLink);
    }, { passive: true });
  }

  // --- Mobile Menu Toggle ---
  if (navToggle && navLinks) {
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
      const hero = document.querySelector('.hero');
      if (hero) {
        const heroHeight = hero.offsetHeight;
        if (scrollY < heroHeight) {
          const parallax = scrollY * 0.25;
          heroBg.style.transform = 'translateY(-' + parallax + 'px)';
        }
      }
    }, { passive: true });
  }

  // --- Contact Form Handling (WordPress AJAX) ---
  if (contactForm && typeof themeAjax !== 'undefined') {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      // Client-side validation
      var name = document.getElementById('contactName');
      var email = document.getElementById('contactEmail');
      var message = document.getElementById('contactMessage');
      var isValid = true;

      // Reset styles
      [name, email, message].forEach(function (field) {
        if (field) field.style.borderColor = '';
      });

      if (!name || !name.value.trim()) {
        if (name) name.style.borderColor = '#f44336';
        isValid = false;
      }

      if (!email || !email.value.trim() || !isValidEmail(email.value)) {
        if (email) email.style.borderColor = '#f44336';
        isValid = false;
      }

      if (!message || !message.value.trim()) {
        if (message) message.style.borderColor = '#f44336';
        isValid = false;
      }

      if (!isValid) {
        showFormStatus('Please fill in all required fields correctly.', 'error');
        return;
      }

      // WordPress AJAX submission
      var submitBtn = document.getElementById('contactSubmit');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';
      }

      var formData = new FormData(contactForm);
      formData.append('action', 'tig_contact_form');
      formData.append('nonce', themeAjax.nonce);

      fetch(themeAjax.url, {
        method: 'POST',
        body: formData,
      })
      .then(function (response) { return response.json(); })
      .then(function (data) {
        if (data.success) {
          showFormStatus(data.data || 'Thank you! Your message has been sent.', 'success');
          contactForm.reset();
        } else {
          showFormStatus(data.data || 'Something went wrong. Please try again.', 'error');
        }
      })
      .catch(function () {
        showFormStatus('Something went wrong. Please try contacting us via email directly.', 'error');
      })
      .finally(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = 'Send Message <span>→</span>';
        }
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

  // --- Smooth scroll for anchor links ---
  document.querySelectorAll('a[href*="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var href = this.getAttribute('href');
      // Only handle same-page anchors
      var hashIndex = href.indexOf('#');
      if (hashIndex === -1) return;

      var targetId = href.substring(hashIndex + 1);
      if (!targetId) return;

      var target = document.getElementById(targetId);
      if (!target) return; // Let browser handle cross-page navigation

      e.preventDefault();
      var navHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-height')) || 80;
      var targetPosition = target.offsetTop - navHeight;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });
    });
  });

  // --- Initial state ---
  if (navbar) updateNav();
  updateActiveLink();

})();
