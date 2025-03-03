
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
</head>
<body>
<div class="signin-container">
  <div class="signin-card">
    <!-- Header -->
    <div class="signin-header">
      <h2>Welcome Back!</h2>
      <p>Sign in to continue your journey</p>
    </div>

    <!-- Sign-In Form -->
    <form id="signinForm" class="signin-form">
      <!-- Email Input -->
      <div class="input-group">
        <input type="email" id="email" name="email" required>
        <label for="email">Email Address</label>
        <span class="icon">✉️</span>
      </div>

      <!-- Password Input -->
      <div class="input-group">
        <input type="password" id="password" name="password" required>
        <label for="password">Password</label>
        <span class="icon">🔒</span>
      </div>

      <!-- Remember Me & Forgot Password -->
      <div class="form-options">
        <label class="remember-me">
          <input type="checkbox" name="rememberMe"> Remember Me
        </label>
        <a href="#" class="forgot-password">Forgot Password?</a>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="signin-button">Sign In</button>


  
    </form>
  </div>
</div>

<style>
/* General Styles */
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  color: #333;
}

.signin-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.signin-card {
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  padding: 2.5rem;
  width: 100%;
  max-width: 400px;
  text-align: center;
}

.signin-header h2 {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  color: #1a237e;
}

.signin-header p {
  color: #666;
  margin-bottom: 2rem;
}

/* Input Fields */
.input-group {
  position: relative;
  margin-bottom: 1.5rem;
}

.input-group input {
  width: 100%;
  padding: 1rem 1rem 1rem 2.5rem;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  font-size: 1rem;
  outline: none;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.input-group input:focus {
  border-color: #3a86ff;
  box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
}

.input-group label {
  position: absolute;
  top: 50%;
  left: 2.5rem;
  transform: translateY(-50%);
  color: #999;
  pointer-events: none;
  transition: all 0.3s;
}

.input-group input:focus + label,
.input-group input:not(:placeholder-shown) + label {
  top: 0;
  left: 1rem;
  font-size: 0.8rem;
  color: #3a86ff;
  background: #fff;
  padding: 0 0.5rem;
}

.input-group .icon {
  position: absolute;
  top: 50%;
  left: 1rem;
  transform: translateY(-50%);
  font-size: 1.2rem;
  color: #999;
}

/* Form Options */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #666;
}

.forgot-password {
  color: #3a86ff;
  text-decoration: none;
  font-size: 0.9rem;
}

.forgot-password:hover {
  text-decoration: underline;
}

/* Sign-In Button */
.signin-button {
  width: 100%;
  padding: 1rem;
  background: #3a86ff;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
}

.signin-button:hover {
  background: #2563eb;
  transform: translateY(-2px);
}

/* Social Login */
.social-login {
  margin: 1.5rem 0;
}

.social-login p {
  color: #666;
  margin-bottom: 1rem;
}

.social-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
}

.social-button {
  padding: 0.5rem 1rem;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  background: transparent;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
}

.social-button:hover {
  background: #f7f7f7;
  transform: translateY(-2px);
}

.social-button.google {
  color: #db4437;
}

.social-button.facebook {
  color: #1877f2;
}

.social-button.apple {
  color: #000;
}

/* Sign-Up Link */
.signup-link {
  margin-top: 1.5rem;
  color: #666;
}

.signup-link a {
  color: #3a86ff;
  text-decoration: none;
}

.signup-link a:hover {
  text-decoration: underline;
}
</style>

<script>
// Form Submission Handler
document.getElementById('signinForm').addEventListener('submit', (e) => {
  e.preventDefault();
  alert('Signed in successfully!');
  // Add logic to handle sign-in (e.g., API call)
});
</script>
</body>
</html>