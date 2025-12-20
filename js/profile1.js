document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("profile-form");
  const avatarInput = document.getElementById("avatar-input");
  const saveBtn = document.getElementById("save-btn");
  const avatarPreview = document.getElementById("avatar-preview");

  // ================= LOAD PROFILE =================
  async function loadProfile() {
    try {
      const res = await fetch("profile_api.php");
      const user = await res.json();

      if (user.status === "error") {
        window.location.href = "login.php";
        return;
      }

      document.getElementById("full_name").value = user.full_name ?? "";
      document.getElementById("email").value = user.email ?? "";
      document.getElementById("bio").value = user.bio ?? "";
      document.getElementById("country").value = user.country ?? "Iraq";
      document.getElementById("newsletter").checked = user.newsletter == 1;

      document.getElementById("side-name").innerText =
        user.full_name ?? "User";
      document.getElementById("side-email").innerText =
        user.email ?? "";

      if (user.gender) {
        const radio = document.querySelector(
          `input[name="gender"][value="${user.gender}"]`
        );
        if (radio) radio.checked = true;
      }

      avatarPreview.src = user.profile_pic
        ? "uploads/" + user.profile_pic
        : "uploads/default_avatar.png";

    } catch {
      showToast("Failed to load profile", "error");
    }
  }

  loadProfile();

  // ================= AVATAR PREVIEW =================
  avatarInput.addEventListener("change", () => {
    const file = avatarInput.files[0];
    if (file) {
      avatarPreview.src = URL.createObjectURL(file);
    }
  });

  // ================= SUBMIT FORM =================
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const pass = document.getElementById("new_password").value;
    const confirm = document.getElementById("confirm_password").value;

    if (pass && pass !== confirm) {
      showToast("Passwords do not match", "error");
      return;
    }

    saveBtn.disabled = true;
    saveBtn.innerText = "Saving...";

    const formData = new FormData(form);

    try {
      const res = await fetch("profile_api.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();
      showToast(result.message, result.status);

      if (result.status === "success") loadProfile();
    } catch {
      showToast("Server error", "error");
    } finally {
      saveBtn.disabled = false;
      saveBtn.innerText = "Save Changes";
    }
  });

  // ================= TOAST =================
  function showToast(message, type = "success") {
    const container = document.getElementById("toast-container");
    const toast = document.createElement("div");

    toast.className = `toast ${type}`;
    toast.innerText = message;

    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = "0";
      toast.style.transform = "translateX(40px)";
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  }
});
