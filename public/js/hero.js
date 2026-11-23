document.addEventListener("DOMContentLoaded", function () {
  const heroInner = document.querySelector(".cq-hero-inner");
  const heroImg = document.querySelector(".cq-hero-img");
  const brainBoost = document.querySelector(".cq-brain-boost");
  const boostWord = document.querySelector(".cq-boost");

  if (!heroInner) return;

  // Fade / slide hero in when it enters the viewport
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        heroInner.style.opacity = "1";
        heroInner.style.transform = "translateY(0)";

        // start gentle float on hippo
        if (heroImg) heroImg.classList.add("cq-float-on");

        observer.disconnect();
      });
    },
    { threshold: 0.3 }
  );

  observer.observe(heroInner);

  // Every 10s, wiggle "Brain Boost" and highlight "Boost"
  if (brainBoost && boostWord) {
    setInterval(() => {
      brainBoost.classList.add("cq-wiggle-on");
      boostWord.classList.add("cq-highlight-on");

      setTimeout(() => {
        brainBoost.classList.remove("cq-wiggle-on");
        boostWord.classList.remove("cq-highlight-on");
      }, 1000); // duration of CSS animations
    }, 10000); // repeat every 10 seconds
  }
});


// NAVBAR

document.addEventListener("DOMContentLoaded", function () {
  // sections we care about
  const sectionIds = ["home", "About", "courses", "teachers", "student", "AI"];
  const sections = sectionIds
    .map((id) => document.getElementById(id))
    .filter(Boolean);

  const navLinks = document.querySelectorAll("header nav a.nav-link");

  if (!sections.length || !navLinks.length) return;

  function linkForId(id) {
    return Array.from(navLinks).find(
      (link) => link.getAttribute("href") === `#${id}`
    );
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        // remove active from all
        navLinks.forEach((l) => l.classList.remove("active"));

        const match = linkForId(entry.target.id);
        if (match) match.classList.add("active");
      });
    },
    {
      threshold: 0.5, // section at least 50% visible
    }
  );

  sections.forEach((sec) => observer.observe(sec));
});


// Scroll reveal for sections
document.addEventListener("DOMContentLoaded", function () {
  const revealEls = document.querySelectorAll(".cq-reveal");
  if (!revealEls.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("cq-reveal-visible");
          observer.unobserve(entry.target); // animate once
        }
      });
    },
    { threshold: 0.25 }
  );

  revealEls.forEach((el) => observer.observe(el));
});



