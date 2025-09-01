<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(111, 202, 203);">
        <h5 class="modal-title" id="forgotPasswordModal">Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="partials/reset_password.php" method="POST">
          <div class="text-left my-2">
            <b><label for="forgotEmail">Email</label></b>
            <input class="form-control" id="forgotEmail" name="email" placeholder="Enter Your Email" type="email" required>
          </div>
          <div class="text-left my-2">
            <b><label for="forgotPhone">Phone Number</label></b>
            <input class="form-control" id="forgotPhone" name="phone" placeholder="Enter Your Phone Number" type="tel" 
                  pattern="\d{10}" maxlength="10" required title="Please enter exactly 10 digits." oninput="this.value = this.value.replace(/[^0-9]/g, '');">
        </div>
          <div class="text-left my-2">
            <b><label for="newPassword">New Password</label></b>
            <div class="input-group">
              <input class="form-control" id="newPassword" name="new_password" placeholder="Enter New Password" type="password" required 
                     title="Password must be 6-10 characters with at least 1 uppercase letter, 1 number, and 1 special character">
              <div class="input-group-append">
                <span class="input-group-text toggle-password" onclick="togglePassword('newPassword', this)">
                  <i class="fa fa-eye"></i>
                </span>
              </div>
            </div>
            <small class="form-text text-muted"></small>
          </div>
          <div class="text-left my-2">
            <b><label for="confirmPassword">Confirm Password</label></b>
            <div class="input-group">
              <input class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm New Password" type="password" required>
              <div class="input-group-append">
                <span class="input-group-text toggle-password" onclick="togglePassword('confirmPassword', this)">
                  <i class="fa fa-eye"></i>
                </span>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success">Reset Password</button>
        </form>
        <p class="mb-0 mt-1">Remembered your password? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#loginModal">Login here</a>.</p>
      </div>
    </div>
  </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- JavaScript for Show/Hide Password -->
<script>
  function togglePassword(fieldId, icon) {
    var field = document.getElementById(fieldId);
    if (field.type === "password") {
      field.type = "text";
      icon.innerHTML = '<i class="fa fa-eye-slash"></i>';
    } else {
      field.type = "password";
      icon.innerHTML = '<i class="fa fa-eye"></i>';
    }
  }
</script>