document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll("[data-shortcut]");

  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      const action = button.dataset.shortcut;

      handleGatewayAction(action);
    });
  });

  document.addEventListener("keydown", (event) => {
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

    if (event.key.toLowerCase() === "g" && !event.ctrlKey && !event.altKey) {
      const activeElement = document.activeElement;

      const isTyping =
        activeElement &&
        (activeElement.tagName === "INPUT" ||
          activeElement.tagName === "TEXTAREA" ||
          activeElement.tagName === "SELECT");

      if (!isTyping) {
        event.preventDefault();

        handleGatewayAction("goto");
      }
    }
  });
});

function handleGatewayAction(action) {
  switch (action) {
    case "company":
      window.location.href = "/Internship/FinnServ/company/";

      break;

    case "date":
      alert("Date selection will be implemented in the next step.");

      break;

    case "goto":
      alert(
        "Go To navigation will be implemented with the Gateway navigation system.",
      );

      break;

    case "help":
      alert("FinnServ keyboard shortcuts and help will be implemented here.");

      break;

    default:
      console.log("Gateway action:", action);
  }
}
