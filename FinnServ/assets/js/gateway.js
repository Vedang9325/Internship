document.addEventListener("DOMContentLoaded", () => {
  // 1. Inject custom styling for active keyboard items dynamically
  const style = document.createElement("style");
  style.textContent = `
    .keyboard-active {
      background-color: #fbbf24 !important;
      color: #111827 !important;
      outline: 2px solid #2563eb !important;
      outline-offset: -2px;
    }
    a.keyboard-active {
      text-decoration: none !important;
    }
    tr.keyboard-active {
      background-color: #fbbf24 !important;
      color: #111827 !important;
    }
    tr.keyboard-active td, tr.keyboard-active td strong, tr.keyboard-active td a {
      color: #111827 !important;
    }
  `;
  document.head.appendChild(style);

  // 2. Identify if there is a form on the page
  const form = document.querySelector("form");
  const inputs = Array.from(
    document.querySelectorAll(
      'form input:not([type="hidden"]):not([readonly]):not([disabled]), form textarea:not([readonly]):not([disabled]), form select:not([disabled]), form button[type="submit"], form .company-action-button'
    )
  );

  if (form && inputs.length > 0) {
    // FORM NAVIGATION MODE
    // Auto-focus first input
    inputs[0].focus();

    inputs.forEach((input, index) => {
      input.addEventListener("keydown", (event) => {
        if (event.key === "Enter") {
          // If it's a textarea, let Enter work normally unless user holds Ctrl or Shift
          if (input.tagName === "TEXTAREA" && !event.ctrlKey && !event.shiftKey) {
            return;
          }

          // If it's the submit button or the very last input, let it submit
          if (input.type === "submit" || index === inputs.length - 1) {
            return;
          }

          event.preventDefault();
          const nextInput = inputs[index + 1];
          if (nextInput) {
            nextInput.focus();
            if (nextInput.select) nextInput.select();
          }
        } else if (event.key === "ArrowDown" && input.tagName !== "TEXTAREA" && input.tagName !== "SELECT") {
          event.preventDefault();
          const nextInput = inputs[index + 1];
          if (nextInput) {
            nextInput.focus();
            if (nextInput.select) nextInput.select();
          }
        } else if (
          (event.key === "ArrowUp" && input.tagName !== "TEXTAREA" && input.tagName !== "SELECT") ||
          (event.key === "Enter" && event.shiftKey)
        ) {
          event.preventDefault();
          const prevInput = inputs[index - 1];
          if (prevInput) {
            prevInput.focus();
            if (prevInput.select) prevInput.select();
          }
        }
      });
    });
  } else {
    // MENU / LIST NAVIGATION MODE
    // Query all potential menu and list items
    const items = Array.from(
      document.querySelectorAll(
        ".gateway-menu-item:not(.disabled), .gateway-quit, .company-menu-item, .company-menu-back, .company-select-item, .company-action-button, .btn, .action-link, table.data-table tbody tr"
      )
    ).filter(el => {
      // Ensure element is visible and not hidden
      return el.offsetWidth > 0 && el.offsetHeight > 0;
    });

    let activeIndex = 0;

    const setActive = (idx) => {
      if (items.length === 0) return;
      // Clamp index
      if (idx < 0) idx = items.length - 1;
      if (idx >= items.length) idx = 0;

      activeIndex = idx;

      // Remove keyboard-active class from all
      items.forEach((item) => {
        item.classList.remove("keyboard-active");
      });

      // Highlight current
      const activeItem = items[activeIndex];
      activeItem.classList.add("keyboard-active");
      
      // Focus if it's focusable
      if (typeof activeItem.focus === "function") {
        activeItem.focus();
      }
      activeItem.scrollIntoView({ block: "nearest" });
    };

    if (items.length > 0) {
      setActive(0);

      document.addEventListener("keydown", (event) => {
        // Don't intercept if focused on inputs
        if (
          document.activeElement &&
          (document.activeElement.tagName === "INPUT" ||
            document.activeElement.tagName === "TEXTAREA" ||
            document.activeElement.tagName === "SELECT")
        ) {
          return;
        }

        if (event.key === "ArrowDown") {
          event.preventDefault();
          setActive(activeIndex + 1);
        } else if (event.key === "ArrowUp") {
          event.preventDefault();
          setActive(activeIndex - 1);
        } else if (event.key === "Enter") {
          event.preventDefault();
          const activeItem = items[activeIndex];
          if (activeItem) {
            if (activeItem.tagName === "A") {
              activeItem.click();
              window.location.href = activeItem.href;
            } else if (activeItem.tagName === "TR") {
              // Find first link inside table row
              const link = activeItem.querySelector("a");
              if (link) {
                link.click();
                window.location.href = link.href;
              }
            } else {
              activeItem.click();
            }
          }
        }
      });
    }

    // Register Hotkeys from underlined tags <u>X</u>
    const allLinks = Array.from(document.querySelectorAll("a, button, span, div"));
    allLinks.forEach((el) => {
      const uTag = el.querySelector("u");
      if (uTag) {
        const char = uTag.textContent.trim().toLowerCase();
        if (char) {
          document.addEventListener("keydown", (event) => {
            // Don't intercept if focused on inputs
            if (
              document.activeElement &&
              (document.activeElement.tagName === "INPUT" ||
                document.activeElement.tagName === "TEXTAREA" ||
                document.activeElement.tagName === "SELECT")
            ) {
              return;
            }

            if (event.key.toLowerCase() === char && !event.ctrlKey && !event.altKey && !event.metaKey) {
              event.preventDefault();
              el.click();
              if (el.href) {
                window.location.href = el.href;
              }
            }
          });
        }
      }
    });
  }

  // 3. Global Shortcuts & Escape Handler
  document.addEventListener("keydown", (event) => {
    // Global Shortcut Keys (F1, F2, F3)
    if (event.key === "F1") {
      event.preventDefault();
      handleGatewayAction("help");
      return;
    }

    if (event.key === "F2") {
      event.preventDefault();
      handleGatewayAction("date");
      return;
    }

    if (event.key === "F3") {
      event.preventDefault();
      handleGatewayAction("company");
      return;
    }

    // Escape Key - Navigate Back / Quit
    if (event.key === "Escape") {
      event.preventDefault();
      // Try to find a back / quit button
      const backButton = document.querySelector(
        '.company-menu-back, .company-action-button[href*="menu"], .company-action-button[href*="dashboard"], a[href*="dashboard"], a.company-menu-back'
      );
      if (backButton) {
        backButton.click();
        if (backButton.href) {
          window.location.href = backButton.href;
        }
      } else {
        // Fallback to history back or dashboard redirect
        if (window.location.pathname.includes("dashboard")) {
          // Already on dashboard, do nothing or prompt logout
        } else {
          window.location.href = "/Internship/FinnServ/dashboard/";
        }
      }
    }
  });

  // Short-cut click actions
  const shortcutButtons = document.querySelectorAll("[data-shortcut]");
  shortcutButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const action = button.dataset.shortcut;
      handleGatewayAction(action);
    });
  });
});

function handleGatewayAction(action) {
  switch (action) {
    case "company":
      window.location.href = "/Internship/FinnServ/company/menu.php";
      break;
    case "date":
      alert("Date selection will be implemented in the next step.");
      break;
    case "goto":
      alert("Go To navigation will be implemented with the Gateway navigation system.");
      break;
    case "help":
      alert("FinnServ keyboard shortcuts and help will be implemented here.");
      break;
    default:
      console.log("Gateway action:", action);
  }
}
