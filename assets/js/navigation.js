document.addEventListener("DOMContentLoaded", function () {
  let currentUrl = window.location.pathname + window.location.search;
  let navItems = document.querySelectorAll(".nav-item");

  // Remove existing active classes before applying new ones
  document.querySelectorAll(".nav-item.active, .nav-link:not(.collapsed), .collapse.show, .collapse-item.active").forEach(el => {
    el.classList.remove("active", "show", "collapsed");
  });

  navItems.forEach((navItem) => {
    let navLink = navItem.querySelector(".nav-link");
    let subMenu = navItem.querySelector(".collapse");
    let isActive = false;

    // Check if the main nav link has no collapse group and matches the URL
    if (!subMenu && navLink && navLink.href.includes(currentUrl)) {
      navItem.classList.add("active");
    }

    let subLinks = navItem.querySelectorAll(".collapse-item");
    subLinks.forEach((subLink) => {
      if (subLink.href.includes(currentUrl)) {
        isActive = true;
        subLink.classList.add("active"); // Add active to the clicked item
        if (subMenu) {
          subMenu.classList.add("show");
        }
        let parentCollapse = subMenu ? subMenu.closest(".collapse") : null;
        while (parentCollapse) {
          parentCollapse.classList.add("show");
          let parentNavItem = parentCollapse.closest(".nav-item");
          if (parentNavItem) {
            parentNavItem.classList.add("active");
            let parentNavLink = parentNavItem.querySelector(".nav-link");
            if (parentNavLink) {
              parentNavLink.classList.remove("collapsed");
            }
          }
          parentCollapse = parentNavItem ? parentNavItem.closest(".collapse") : null;
        }
      }
    });

    if (isActive) {
      navItem.classList.add("active");
      if (navLink) {
        navLink.classList.remove("collapsed");
      }
    }
  });
});