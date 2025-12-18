document.getElementById("profileForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const saveBtn = document.getElementById("saveBtn");
  const statusMsg = document.getElementById("statusMsg");

  // Collecting different types of data
  const payload = {
    bio: document.getElementById("bio").value,
    country: document.getElementById("country").value,
    gender:
      document.querySelector('input[name="gender"]:checked')?.value || "other",
    newsletter: document.getElementById("newsletter").checked,
  };

  saveBtn.innerText = "Saving...";
  saveBtn.disabled = true;

  fetch("profile_api.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  })
    .then(async (res) => {
      const result = await res.json();
      statusMsg.innerText = result.message;
      statusMsg.className = res.ok ? "status-success" : "status-error";
      statusMsg.style.display = "block";
    })
    .catch(() => {
      statusMsg.innerText = "Connection Error";
      statusMsg.className = "status-error";
      statusMsg.style.display = "block";
    })
    .finally(() => {
      saveBtn.innerText = "Save Profile";
      saveBtn.disabled = false;
    });
});
