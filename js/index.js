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
  if (isValid) {
    const btn = document.getElementById("submitBtn");
    btn.innerText = "Creating Account...";
    btn.disabled = true;

    setTimeout(() => {
      alert("SUCCESS: User created! Redirecting to Dashboard...");
      // Reset form for demo purposes
      document.getElementById("signupForm").reset();
      btn.innerText = "Create Account";
      btn.disabled = false;
    }, 1000);
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
