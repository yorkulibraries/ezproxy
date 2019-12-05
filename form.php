<?php include('template_top.php'); ?>

<?php $dest_url = $_REQUEST['qurl']; ?>
<?php $barcode = $_POST['barcode']; ?>
<?php $pin = $_POST['pin']; ?>

      <div class="row">
        <div class="col-lg-12">
          <div class="mt-4 card shadow-sm">
            <div class="card-body">
              <div class="text-center mt-5">
                <h1 class="h3 mb-3 font-weight-normal text-center">Please sign in</h1>

  <?php foreach ($errors as $e) { ?>
    <p class="text-danger"><?php echo $e; ?></p>
  <?php } ?>

                <form class="simple_form form-signin" id="new_user" novalidate="novalidate" action="<?= $_SERVER['REQUEST_URI']; ?>" accept-charset="UTF-8" method="post">
                  <div class="form-inputs">
                    <div class="input string optional user_username"><input class="string optional form-control username" autocomplete="false" placeholder="Library Barcode" autofocus="autofocus" type="text" name="barcode" id="user_username" value="<?php echo htmlspecialchars($barcode) ?>"/></div>
                    <div class="input password optional user_password"><input class="password optional form-control password" autocomplete="current-password" placeholder="Password" type="password" name="pin" id="user_password" value="<?php echo htmlspecialchars($pin) ?>" /></div>
                  </div>

                  <!-- Google reCAPTCHA box -->
                  <div class="g-recaptcha" data-sitekey="<?= $recaptcha_site_key; ?>"></div>

                  <input type="submit" name="commit" value="Log in" class="btn btn btn-lg btn-primary btn-block" data-disable-with="Log in" />
 
                  <div class="mt-4 border-top pt-2">
                    <a class="text-muted" href="passport?qurl=<?= urlencode($dest_url) ?>">Login with Passport York</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>



  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php include('template_bottom.php'); ?>
