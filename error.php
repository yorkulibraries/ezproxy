<?php include('template_top.php'); ?>

      <div class="row">
        <div class="col-lg-12">
          <div class="mt-4 card shadow-sm">
            <div class="card-body">
              <div class="text-center mt-5">
                <h1 class="h3 mb-3 font-weight-normal text-center">ERROR</h1>

  <?php foreach ($errors as $e) { ?>
    <p class="text-danger"><?php echo $e; ?></p>
  <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>


<?php include('template_bottom.php'); ?>
