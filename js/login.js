document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const emailInput = document.getElementById("loginEmail");
  const passInput = document.getElementById("loginPassword");
  const errorBox = document.getElementById("errorBox");
  const loginBtn = document.getElementById("loginBtn");

  clearLoginErrors([emailInput, passInput], errorBox);

  const email = emailInput.value.trim();
  const pass = passInput.value.trim();

  if (!email || !pass) {
    showLoginError(errorBox, "Please fill in all fields", "orange");
    return;
  }

  const payload = { email: email, password: pass };

  loginBtn.innerText = "Authenticating...";
  loginBtn.disabled = true;

  fetch("login_api.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  })
    .then(async (response) => {
      const data = await response.json();

      if (response.status === 200) {
        loginBtn.innerText = "Redirecting...";
        window.location.href = "homepage.php";
      } else if (response.status === 401) {
        showLoginError(errorBox, "❌ " + data.message, "red");
        highlightError(emailInput, passInput);
      } else {
        showLoginError(
          errorBox,
          "🚨 " + (data.message || "Server Error"),
          "red"
        );
      }
    })
    .catch((error) => {
      showLoginError(errorBox, "🌐 Connection Error", "red");
    })
    .finally(() => {
      if (!window.location.href.includes("home.php")) {
        loginBtn.innerText = "Sign In";
        loginBtn.disabled = false;
      }
    });
});

/** * NEW: PASSWORD TOGGLE LOGIC
 * This looks for the button we put in the HTML
 */
const toggleBtn = document.getElementById("togglePass");
const passInput = document.getElementById("loginPassword");

if (toggleBtn) {
  toggleBtn.addEventListener("click", function () {
    // Switch between 'password' and 'text' types
    const isPassword = passInput.type === "password";
    passInput.type = isPassword ? "text" : "password";

    // Update button text
    toggleBtn.innerText = isPassword ? "Hide" : "Show";
  });
}

/** UI HELPER FUNCTIONS **/
function showLoginError(box, message, colorCode) {
  box.innerText = message;
  box.style.display = "block";
  box.style.color = colorCode;
  box.style.border = `1px solid ${colorCode}`;
}

function clearLoginErrors(inputs, box) {
  box.style.display = "none";
  box.innerText = "";
  box.style.border = "none";
  inputs.forEach((input) => {
    input.style.borderColor = "#ddd";
  });
}

function highlightError(...inputs) {
  inputs.forEach((input) => {
    input.style.borderColor = "red";
  });
}
