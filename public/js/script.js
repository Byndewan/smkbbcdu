// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function() {
  // Preloader
  const preloader = document.getElementById("preloader");
  if (preloader) {
    setTimeout(() => {
      preloader.style.opacity = "0";
      preloader.style.pointerEvents = "none";
      setTimeout(() => {
        preloader.remove();
      }, 300);
    }, 500);
  }

  // Get elements
  const scrollToTopBtn = document.getElementById("scrollToTopBtn");
  const whatsappBtn = document.getElementById("whatsappBtn");
  const heroSection = document.getElementById("heroSection");
  const sidebar = document.getElementById("mobileSidebar");
  const overlay = document.getElementById("overlay");
  const hamburgerBtn = document.getElementById("hamburgerBtn");
  const closeSidebarBtn = document.getElementById("closeSidebar");
  const counters = document.querySelectorAll(".counter");
  const floatingElements = document.querySelectorAll(".floating");

  // Mobile sidebar functionality
  function toggleSidebar() {
    sidebar.classList.toggle("-translate-x-full");
    overlay.classList.toggle("hidden");
    document.body.classList.toggle("overflow-hidden");
  }

  function closeSidebar() {
    sidebar.classList.add("-translate-x-full");
    overlay.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
  }

  if (hamburgerBtn) {
    hamburgerBtn.addEventListener("click", toggleSidebar);
  }

  if (closeSidebarBtn) {
    closeSidebarBtn.addEventListener("click", closeSidebar);
  }

  if (overlay) {
    overlay.addEventListener("click", closeSidebar);
  }

  // Scroll events
  if (heroSection && scrollToTopBtn && whatsappBtn) {
    window.addEventListener("scroll", function() {
      const heroBottom = heroSection.offsetTop + heroSection.offsetHeight;
      const isPastHero = window.scrollY > heroBottom;

      scrollToTopBtn.classList.toggle("hidden", !isPastHero);

      if (isPastHero) {
        whatsappBtn.classList.add("bottom-24");
        whatsappBtn.classList.remove("bottom-6");
      } else {
        whatsappBtn.classList.remove("bottom-24");
        whatsappBtn.classList.add("bottom-6");
      }
    });
  }

  // Scroll to top button
  if (scrollToTopBtn) {
    scrollToTopBtn.querySelector("button").addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function(e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");
      const target = document.querySelector(targetId);

      if (target) {
        window.scrollTo({
          top: target.offsetTop - 80,
          behavior: "smooth"
        });
        closeSidebar();
      }
    });
  });

  // FAQ accordion functionality
  const faqToggles = document.querySelectorAll(".faq-toggle");
  if (faqToggles.length > 0) {
    // Open first FAQ item by default
    const firstItem = document.querySelector(".faq-item");
    if (firstItem) {
      const firstContent = firstItem.querySelector(".faq-content");
      const firstIcon = firstItem.querySelector(".faq-icon");
      firstContent.style.maxHeight = firstContent.scrollHeight + "px";
      firstIcon.textContent = "−";
      firstItem.classList.add("border-l-4", "border-l-red-600");
    }

    faqToggles.forEach((toggle) => {
      toggle.addEventListener("click", () => {
        const faqItem = toggle.closest(".faq-item");
        const content = faqItem.querySelector(".faq-content");
        const icon = toggle.querySelector(".faq-icon");

        // Close all other items
        document.querySelectorAll(".faq-item").forEach((item) => {
          if (item !== faqItem) {
            const itemContent = item.querySelector(".faq-content");
            const itemIcon = item.querySelector(".faq-icon");
            itemContent.style.maxHeight = "0px";
            itemIcon.textContent = "+";
            item.classList.remove("border-l-4", "border-l-red-600");
          }
        });

        // Toggle current item
        if (content.style.maxHeight && content.style.maxHeight !== "0px") {
          content.style.maxHeight = "0px";
          icon.textContent = "+";
          faqItem.classList.remove("border-l-4", "border-l-red-600");
        } else {
          content.style.maxHeight = content.scrollHeight + "px";
          icon.textContent = "−";
          faqItem.classList.add("border-l-4", "border-l-red-600");
        }
      });
    });
  }

  // Counter animation
  if (counters.length > 0) {
    const counterObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const counter = entry.target;
            const target = +counter.getAttribute("data-target");
            const duration = 2000; // 2 seconds
            const stepTime = 20;
            const steps = duration / stepTime;
            const increment = target / steps;
            let current = 0;

            const updateCounter = setInterval(() => {
              current += increment;
              if (current >= target) {
                clearInterval(updateCounter);
                counter.textContent = target.toLocaleString();
              } else {
                counter.textContent = Math.ceil(current).toLocaleString();
              }
            }, stepTime);

            counterObserver.unobserve(counter);
          }
        });
      },
      { threshold: 0.5 }
    );

    counters.forEach((counter) => {
      counterObserver.observe(counter);
    });
  }

  // Floating elements animation
  if (floatingElements.length > 0) {
    floatingElements.forEach((el) => {
      el.style.animationDelay = `${Math.random() * 2}s`;
    });
  }
});
