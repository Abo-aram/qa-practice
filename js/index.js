document.getElementById("signupForm").addEventListener("submit", function (e) {
  e.preventDefault(); // Stop page reload

  // clear previous errors
  clearErrors();

  // Get values
  const fullName = document.getElementById("fullName").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  let isValid = true;

  // 1. Validate Name
  // 1. Validate Name
  if (fullName === "") {
    showError("fullName", "nameError", "Name is required");
    isValid = false;
  } else if (fullName.length < 2) {
    // <--- NEW FIX
    showError("fullName", "nameError", "Name must be at least 2 characters");
    isValid = false;
  }

  // 2. Validate Email (Basic Regex)
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    showError("email", "emailError", "Please enter a valid email");
    isValid = false;
  }

  // 3. Validate Password Strength & Match
  if (password === "") {
    showError("password", "passError", "Password is required");
    isValid = false;
  } else if (password.length < 8) {
    // <--- NEW FIX HERE
    showError(
      "password",
      "passError",
      "Password must be at least 8 characters"
    );
    isValid = false;
  } else if (password !== confirmPassword) {
    showError("confirmPassword", "confirmPassError", "Passwords do not match");
    isValid = false;
  }

  // 4. Simulate Backend Submission
  // 4. REAL Backend Submission via API
  if (isValid) {
    const btn = document.getElementById("submitBtn");
    const statusMsg = document.getElementById("mainStatus");

    btn.innerText = "Connecting to Server...";
    btn.disabled = true;

    // The data we are sending
    const payload = {
      fullName: fullName,
      email: email,
      password: password,
    };

    // Send data to PHP using Fetch API
    // ... (Your validation logic remains the same)

    if (isValid) {
      const statusMsg = document.getElementById("mainStatus");

      fetch("register_api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      })
        .then(async (response) => {
          // 1. Get the JSON data regardless of status
          const data = await response.json();

          console.log("Response Data:", response); // Debugging lineq

          // 2. Check the Status Codes
          if (response.status === 201) {
            // SUCCESS: Created
            statusMsg.style.color = "green";
            statusMsg.innerText = data.message;
            document.getElementById("signupForm").reset();
            window.location.href = "login.php"; // Redirect to login page
          } else if (response.status === 409) {
            // ERROR: Conflict (Email exists)
            statusMsg.style.color = "orange";
            statusMsg.innerText = "⚠️ " + data.message;
           
          } else if (response.status === 400) {
            // ERROR: Bad Request (Validation failed on server)
            statusMsg.style.color = "red";
            statusMsg.innerText = "❌ " + data.message;
              document.getElementById("signupForm").reset();
          } else {
            // ERROR: 500 or others
            throw new Error(data.message || "Server Error");
              document.getElementById("signupForm").reset();
          }
        })
        .catch((error) => {
          statusMsg.style.color = "red";
          statusMsg.innerText = "🚨 " + error.message;
        }).finally(() => {
          btn.innerText = "Create Account";
          btn.disabled = false;

        })
        ;
    }
  }
});

function showError(inputId, errorId, message) {
  const input = document.getElementById(inputId);
  const errorText = document.getElementById(errorId);

  input.classList.add("error");
  errorText.innerText = message;
  errorText.style.display = "block";
}

function clearErrors() {
  const inputs = document.querySelectorAll("input");
  const errors = document.querySelectorAll(".error-msg");

  inputs.forEach((input) => input.classList.remove("error"));
  errors.forEach((error) => (error.innerText = ""));
}

// Toggle 1: Main Password
const toggleBtn = document.getElementById('togglePass');
const passInput = document.getElementById('password');

toggleBtn.addEventListener('click', function() {
    if (passInput.type === "password") {
        passInput.type = "text";
        toggleBtn.innerText = "Hide";
    } else {
        passInput.type = "password";
        toggleBtn.innerText = "Show";
    }
});

// Toggle 2: Confirm Password    
const toggleConfirmBtn = document.getElementById('toggleConfirm');
const confirmInput = document.getElementById('confirmPassword');

toggleConfirmBtn.addEventListener('click', function() {
    if (confirmInput.type === "password") {
        confirmInput.type = "text";
        toggleConfirmBtn.innerText = "Hide";
    } else {
        confirmInput.type = "password";
        toggleConfirmBtn.innerText = "Show";
    }
});

