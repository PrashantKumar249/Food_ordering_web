// main js called
// console.log("main.js loaded");
document.addEventListener("DOMContentLoaded", function () {
  // Use event delegation for add/remove buttons
  document.body.addEventListener("click", function (e) {
    const target = e.target.closest(".add-to-cart, .remove-from-cart");
    if (!target) return;

    const action = target.classList.contains("add-to-cart") ? "add" : "remove";
    const user_id = target.dataset.userId;
    const menu_item_id = target.dataset.menuId;

    fetch("add_cart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        type: action,
        menu_item_id,
        user_id,
        quantity: 1,
        page_name: "index",
      }),
    })
      .then((res) => res.text())
      .then((response) => {
        let countSpan = document.getElementById(`cart-count-${menu_item_id}`);
        if (countSpan) {
          let currentCount = parseInt(countSpan.textContent);
          if (action === "add") {
            currentCount++;
          } else {
            currentCount = Math.max(currentCount - 1, 0);
          }
          countSpan.textContent = `${currentCount} in cart`;
        }

        showToast(
          action === "add" ? "Item added to cart" : "Item removed from cart",
          action === "add" ? "success" : "error"
        );
      })
      .catch(() => {
        showToast("Action failed", "error");
      });
  });

  function showToast(message, type = "success") {
    const toast = document.createElement("div");
    toast.className = `fixed bottom-5 right-5 px-4 py-2 rounded shadow-lg text-white z-50 transition-opacity duration-300 ease-in-out ${
      type === "success" ? "bg-green-600" : "bg-red-600"
    }`;
    toast.textContent = message;

    // Set initial opacity for animation
    toast.style.opacity = "1";

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = "0";
      setTimeout(() => toast.remove(), 500);
    }, 2000);
  }
});
