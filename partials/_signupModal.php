<!-- Sign up Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(111 202 203);">
        <h5 class="modal-title" id="signupModal">SignUp Here</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="partials/_handleSignup.php" method="post">
          <div class="form-group">
            <b><label for="username">Username</label></b>
            <input class="form-control" id="username" name="username" placeholder="Choose a unique Username" type="text"
              required minlength="3" maxlength="11">
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <b><label for="firstName">First Name:</label></b>
              <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
            </div>
            <div class="form-group col-md-6">
              <b><label for="lastName">Last name:</label></b>
              <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last name" required>
            </div>
          </div>
          <div class="form-group">
            <b><label for="email">Email:</label></b>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email" required>
          </div>
          <div class="form-group">
            <b><label for="phone">Phone No:</label></b>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon">+91</span>
              </div>
              <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Your Phone Number"
                required pattern="[0-9]{10}" maxlength="10">
            </div>
          </div>
          <div class="text-left my-2">
            <b><label for="password">Password:</label></b>
            <div class="input-group">
              <input class="form-control" id="password" name="password" placeholder="Enter Password" type="password"
                required minlength="4" maxlength="21">
              <div class="input-group-append">
                <span class="input-group-text toggle-password" onclick="togglePassword('password', this)">
                  <i class="fa fa-eye"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="text-left my-2">
            <b><label for="cpassword">Re-enter Password:</label></b>
            <div class="input-group">
              <input class="form-control" id="cpassword" name="cpassword" placeholder="Re-enter Password"
                type="password" required minlength="4" maxlength="21">
              <div class="input-group-append">
                <span class="input-group-text toggle-password" onclick="togglePassword('cpassword', this)">
                  <i class="fa fa-eye"></i>
                </span>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-success">Submit</button>
        </form>
        <p class="mb-0 mt-1">Already have an account? <a href="#" data-dismiss="modal" data-toggle="modal"
            data-target="#loginModal">Login here</a>.</p>
        <p class="mb-0 mt-1">Want to deliver with us? <a href="#" data-dismiss="modal" data-toggle="modal"
            data-target="#deliveryboySignupModal">Apply here for delivery</a>.</p>
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