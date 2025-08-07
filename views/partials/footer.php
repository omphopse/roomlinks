<footer class="footer-main">
  <p>
    &copy; <span id="currentYear"></span> Copyright reserved RoomLinks | Powered By <a href="https://elephantroots.com/"
      target="_blank" class="footer-link">ElephantRoots</a>
  </p>
</footer>
<?php
  // Auto-open modal if session message exists and user is NOT logged in
  $showModal = '';
  $isLoggedIn = !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
  if (!$isLoggedIn) {
    if (isset($_SESSION['error'])) {
      $showModal = "register";
      unset($_SESSION['error']);
    } elseif (isset($_SESSION['success'])) {
      $showModal = "login";
      unset($_SESSION['success']);
    }
  }
?>
<script>
  <?php if ($showModal): ?>
    window.addEventListener('DOMContentLoaded', function () {
      showAuthModal('<?php echo $showModal; ?>');
    });
  <?php endif; ?>
  // Set current year in footer
  document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>

<!-- Login/Register Popup Modal -->

<div id="authModal"
  class="fixed inset-0 z-[9999] hidden bg-white md: bg-[--secondary-color] w-screen h-screen flex items-center justify-center overflow-y-auto">
  <button id="closeAuthModal"
    class="absolute top-4 right-6 bg-transparent border-0 text-3xl text-gray-400 hover:text-gray-700 z-50 cursor-pointer">&times;</button>
  <div
    class="w-full lg:max-w-6xl md:max-w-2xl bg-white  rounded-xl md:shadow-2xl flex flex-col md:flex-row overflow-hidden relative md:h-auto">
    <!-- Left: Image -->
    <div class="hidden md:flex md:w-1/2 min-h-full aspect-square md:aspect-auto">
      <img src="assets/banner/roommates.png" alt="Roommates" class="object-cover w-full h-full" />
    </div>
    <!-- Right: Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center p-8 h-full">
      <h2 id="authModalHeading" class="text-2xl font-bold mb-4 text-primary">Login</h2>
      <!-- Login Form -->
      <form id="loginForm" class="auth-form" method="POST" action="<?php echo APP_URL; ?>/login">
        <div class="mb-4">
          <label for="login-username" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" id="login-username" name="email"
            class="block w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            placeholder="Enter your email" required />
        </div>
        <div class="mb-4">
          <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input type="password" id="login-password" name="password"
              class="block w-full rounded-lg border border-gray-300 px-4 py-2 pr-12 focus:ring-2 focus:ring-blue-400 focus:outline-none"
              placeholder="Enter your password" required autocomplete="current-password" />
            <button type="button" class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 hover:text-gray-700"
              onclick="togglePassword('login-password', this)"><span class="show-hide-icon"><svg
                  xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg></span></button>
          </div>
        </div>
        <div id="loginError" class="text-red-500 text-sm mb-2"></div>
        <script>
          // AJAX login
          document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            const errorDiv = document.getElementById('loginError');
            errorDiv.textContent = '';
            try {
              const res = await fetch(form.action, {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
              });
              const text = await res.text();
              let result;
              try {
                result = JSON.parse(text);
              } catch (jsonErr) {
                errorDiv.textContent = 'Invalid server response. Please contact support.';
                return;
              }
              if (result.success) {
                document.querySelector('.alert-success')?.remove();
                const msg = document.createElement('div');
                msg.className = 'alert alert-success bg-green-100 text-green-800 px-4 py-2 rounded mb-2 ';
                msg.textContent = result.success;
                form.parentNode.insertBefore(msg, form);
               
                // Redirect based on user_role
                if (result.user_role === 'admin') {
                  setTimeout(function() {
                    window.location.href = '<?php echo APP_URL; ?>/admin/dashboard';
                  }, 500);
                } else {
                  setTimeout(() => window.location.reload(), 1000);
                }
              } else if (result.error) {
                errorDiv.textContent = result.error;
              }
            } catch (err) {
              errorDiv.textContent = 'Login failed. Please try again.';
            }
          });
        </script>
        <button type="submit"
          class="w-full bg-primary text-white rounded-lg py-2 font-semibold text-base transition">Login</button>
        <div class="mt-4 text-center text-sm text-gray-600">Don't have an account? <a href="#"
            class="text-blue-600 hover:underline font-medium" onclick="showRegisterForm(); return false;">Register
            here</a></div>
      </form>
      <!-- Register Form -->
      <form id="registerForm" class="auth-form hidden" method="POST" action="<?php echo APP_URL; ?>/register">
        <div class="mb-4">
          <label for="reg-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" id="reg-email" name="email" placeholder="Enter your email"
            class="block w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            required />
        </div>
        <div class="mb-4">
          <label for="reg-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input type="password" id="reg-password" name="password" placeholder="Enter your password"
              class="block w-full rounded-lg border border-gray-300 px-4 py-2 pr-12 focus:ring-2 focus:ring-blue-400 focus:outline-none"
              required autocomplete="new-password" />
            <button type="button" class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 hover:text-gray-700"
              onclick="togglePassword('reg-password', this)"><span class="show-hide-icon"><svg
                  xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg></span></button>
          </div>
        </div>
        <div class="mb-4">
          <label for="reg-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm
            Password</label>
          <div class="relative">
            <input type="password" id="reg-confirm-password" name="confirm_password" placeholder="Confirm your password"
              class="block w-full rounded-lg border border-gray-300 px-4 py-2 pr-12 focus:ring-2 focus:ring-blue-400 focus:outline-none"
              required autocomplete="new-password" />
            <button type="button" class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 hover:text-gray-700"
              onclick="togglePassword('reg-confirm-password', this)"><span class="show-hide-icon"><svg
                  xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg></span></button>
          </div>
        </div>
        <div class="mb-4 flex items-start">
          <input type="checkbox" id="policyCheck" name="policyCheck" class="mt-1 mr-2" />
          <label for="policyCheck" class="text-sm text-gray-700 select-none">I have read the <a href="<?php echo APP_URL; ?>/privacy-policy" target="_blank" class="text-blue-600 underline">Privacy Policy</a> and <a href="<?php echo APP_URL; ?>/termsandconditions" target="_blank" class="text-blue-600 underline">Terms and Conditions</a></label>
        </div>
        <div id="registerError" class="text-red-500 text-sm mb-2"></div>
        <div id="policyError" class="text-red-500 text-sm mb-2"></div>
        <script>
          // AJAX registration with password validation
          document.getElementById('registerForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            const errorDiv = document.getElementById('registerError');
            const policyErrorDiv = document.getElementById('policyError');
            errorDiv.textContent = '';
            policyErrorDiv.textContent = '';
            const policyChecked = document.getElementById('policyCheck').checked;
            if (!policyChecked) {
              policyErrorDiv.textContent = 'Please read and accept the Privacy Policy and Terms and Conditions.';
              return;
            }
            // Password validation
            const password = document.getElementById('reg-password').value;
            const confirmPassword = document.getElementById('reg-confirm-password').value;
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/;
            if (!passwordRegex.test(password)) {
              errorDiv.textContent = 'Password must be at least 6 characters and include a lowercase letter, uppercase letter, number, and symbol.';
              return;
            }
            if (password !== confirmPassword) {
              errorDiv.textContent = 'Passwords do not match.';
              return;
            }
            try {
              const res = await fetch(form.action, {
                method: 'POST',
                body: data,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
              });
              const text = await res.text();
              let result;
              try {
                result = JSON.parse(text);
              } catch (jsonErr) {
                errorDiv.textContent = 'Invalid server response. Please contact support.';
                return;
              }
              if (result.success) {
                document.querySelector('.alert-success')?.remove();
                const msg = document.createElement('div');
                msg.className = 'alert alert-success bg-green-100 text-green-800 px-4 py-2 rounded mb-2 ';
                msg.textContent = result.success;
                form.parentNode.insertBefore(msg, form);
                let redirectUrl = result.redirect;
                setTimeout(() => window.location.href = redirectUrl, 1000);
              } else if (result.error) {
                errorDiv.textContent = result.error;
              }
            } catch (err) {
              errorDiv.textContent = 'Registration failed. Please try again.';
            }
          });
        </script>
        <button type="submit"
          class="w-full bg-primary text-white rounded-lg py-2 font-semibold text-base transition">Register</button>
        <div class="mt-4 text-center text-sm text-gray-600">Already have an account? <a href="#"
            class="text-blue-600 hover:underline font-medium" onclick="showLoginForm(); return false;">Login here</a>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
  // Modal logic
  function showAuthModal(tab = 'login') {
    const modal = document.getElementById('authModal');
    modal.classList.add('flex');
    modal.classList.remove('hidden');
    if (tab === 'register') {
      showRegisterForm();
    } else {
      showLoginForm();
    }
  }
  function hideAuthModal() {
    const modal = document.getElementById('authModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
  }
  document.getElementById('closeAuthModal').onclick = hideAuthModal;
  function showRegisterForm() {
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('registerForm').classList.remove('hidden');
    document.getElementById('authModalHeading').textContent = 'Register';
  }
  function showLoginForm() {
    document.getElementById('loginForm').classList.remove('hidden');
    document.getElementById('registerForm').classList.add('hidden');
    document.getElementById('authModalHeading').textContent = 'Login';
  }

  function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('svg');
    if (input.type === 'password') {
      input.type = 'text';
      btn.title = 'Hide Password';
      icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368m3.087-2.742A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>';
    } else {
      input.type = 'password';
      btn.title = 'Show Password';
      icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
  }
</script>