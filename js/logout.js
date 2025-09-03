function showLogoutModal() {
  document.getElementById("logoutModal").classList.add("show");
}

function hideLogoutModal() {
  document.getElementById("logoutModal").classList.remove("show");
}

function confirmLogout() {
  if (document.getElementById("admin-confirmBtn")) {
      window.location.href = "../includes/admin_logout_handle.php";
  } else {
      window.location.href = "../includes/logout_handle.php";
  }
}

document.getElementById("logoutModal").addEventListener("click", function (e) {
  if (e.target === this) {
    hideLogoutModal();
  }
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") {
    hideLogoutModal();
  }
});
