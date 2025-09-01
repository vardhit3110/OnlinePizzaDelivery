<!-- Delivery Boy Sign up Modal -->
<div class="modal fade" id="deliveryboySignupModal" tabindex="-1" role="dialog" aria-labelledby="deliveryboySignupModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(111 202 203);">
        <h5 class="modal-title" id="deliveryboySignupModal">Apply as a Delivery Boy</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="partials/_handleDeliverySignup.php" method="post">
          <div class="form-group">
            <b><label for="db_delivery_boy_name">Your name</label></b>
            <input class="form-control" id="db_delivery_boy_name" name="db_delivery_boy_name" placeholder="Choose a unique Username" type="text" required minlength="3" maxlength="50">
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <b><label for="db_firstname">First Name:</label></b>
              <input type="text" class="form-control" id="db_firstname" name="db_firstname" placeholder="Enter Your First Name" required maxlength="50">
            </div>
            <div class="form-group col-md-6">
              <b><label for="db_lastname">Last Name:</label></b>
              <input type="text" class="form-control" id="db_lastname" name="db_lastname" placeholder="Enter Your Last Name" required maxlength="50">
            </div>
          </div>

          <div class="form-group">
            <b><label for="db_email">Email:</label></b>
            <input type="email" class="form-control" id="db_email" name="db_email" placeholder="Enter Your Email" required maxlength="100">
          </div>

          <div class="form-group">
            <b><label for="db_phone">Phone No:</label></b>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon">+91</span>
              </div>
              <input type="tel" class="form-control" id="db_phone" name="db_phone" placeholder="Enter Your Phone Number" required pattern="[0-9]{10}" maxlength="10">
            </div>
          </div>

          <div class="form-group">
            <b><label for="vehicle">Do you have a vehicle?</label></b>
            <select class="form-control" id="vehicle" name="vehicle" required>
              <option value="">Select</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
            </select>
          </div>

          <div class="form-group">
            <b><label for="db_password">Password:</label></b>
            <div class="input-group">
              <input class="form-control" id="db_password" name="db_password" placeholder="Enter Password" type="password" required minlength="4" maxlength="21">
              <div class="input-group-append">
                <span class="input-group-text toggle-password" onclick="togglePassword('db_password', this)">
                  <i class="fa fa-eye"></i>
                </span>
              </div>
            </div>
          </div>

          <div class="text-left my-2">
            <b><label for="db_cpassword">Re-enter Password:</label></b>
            <div class="input-group">
              <input class="form-control" id="db_cpassword" name="db_cpassword" placeholder="Re-enter Password" type="password" required minlength="4" maxlength="21">
              <div class="input-group-append">
                <span class="input-group-text toggle-password" onclick="togglePassword('db_cpassword', this)">
                  <i class="fa fa-eye"></i>
                </span>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success" name="submit">Submit</button>
          <p class="mb-0 mt-1">Not For Deliver..!
            <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signupModal">Sign up now</a>.
          </p>
        </form>
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
