<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>InstaFix - Join Us</title>
    <link rel="stylesheet" href="css/index.css">

  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>InstaFix</h1>
        <p>Your on-demand repair hero.</p>
      </div>

      <form id="signupForm">
        <div class="input-group">
          <label for="fullName">Full Name</label>
          <input type="text" id="fullName" placeholder="John Doe" />
          <small class="error-msg" id="nameError"></small>
        </div>

        <div class="input-group">
          <label for="email">Email Address</label>
          <input type="text" id="email" placeholder="john@example.com" />
          <small class="error-msg" id="emailError"></small>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <div style="display: flex; gap: 10px">
            <input type="password" id="password" placeholder="********" />
            <button
              type="button"
              id="togglePass"
              style="width: 60px; padding: 5px; font-size: 0.8rem"
            >
              Show
            </button>
          </div>
          <small class="error-msg" id="passError"></small>
        </div>

        <div class="input-group">
          <label for="confirmPassword">Confirm Password</label>
          <div style="display: flex; gap: 10px">
            <input
              type="password"
              id="confirmPassword"
              placeholder="********"
            />
            <button
              type="button"
              id="toggleConfirm"
              style="width: 60px; padding: 5px; font-size: 0.8rem"
            >
              Show
            </button>
          </div>
          <small class="error-msg" id="confirmPassError"></small>
        </div>

        <button type="submit" id="submitBtn">Create Account</button>

        <p style="text-align: center; margin-top: 15px">
          Already have an account?
          <a href="login.php" id="loginLink">Log In</a>
        </p>
        <p class="status-message" id="mainStatus"></p>
      </form>
    </div>

    <script src="js/index.js"></script>
  </body>
</html>
