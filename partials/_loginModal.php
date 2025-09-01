<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(111 202 203);">
        <h5 class="modal-title" id="loginModal">Login Here</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="partials/_handleLogin.php" method="post">
          <div class="text-left my-2">
            <b><label for="loginusername">Username</label></b>
            <input class="form-control" id="loginusername" name="loginusername" placeholder="Enter Your Username"
              type="text" required>
          </div>
          <div class="text-left my-2">
            <b><label for="loginpassword">Password</label></b>
            <div class="input-group">
              <input class="form-control" id="loginpassword" name="loginpassword" placeholder="Enter Your Password"
                type="password" required>
              <div class="input-group-append">
                <span class="input-group-text toggle-password" onclick="togglePassword('loginpassword', this)">
                  <i class="fa fa-eye"></i>
                </span>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success">Submit</button>
        </form>
        <p class="mb-0 mt-1">Don't have an account? <a href="#" data-dismiss="modal" data-toggle="modal"
            data-target="#signupModal">Sign up now</a>.</p>
        <p class="mb-0 mt-1">Forgot Password? <a href="#" data-dismiss="modal" data-toggle="modal"
            data-target="#forgotPasswordModal">Reset here</a>.</p>
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