<?php 
require_once(__DIR__.'/common.php');

$dest_url = trim($_REQUEST['qurl']); 

if (
(strpos($dest_url, 'http://www.library.yorku.ca/e/resolver/') === 0)
|| (strpos($dest_url, 'https://www.library.yorku.ca/e/resolver/') === 0)
|| (strpos($dest_url, 'http://www.library.yorku.ca/eresolver/') === 0)
|| (strpos($dest_url, 'https://www.library.yorku.ca/eresolver/') === 0)
|| (strpos($dest_url, 'http://ezproxy.library.yorku.ca/login?url=') === 0)
|| (strpos($dest_url, 'https://ezproxy.library.yorku.ca/login?url=') === 0)
|| (strpos($dest_url, 'http://ebookcentral.proquest.com/lib/york/') === 0)
|| (strpos($dest_url, 'https://ebookcentral.proquest.com/lib/york/') === 0)
|| (strpos($dest_url, 'http://site.ebrary.com/lib/oculyork/docDetail.action?docID=') === 0)
|| (strpos($dest_url, 'https://site.ebrary.com/lib/oculyork/docDetail.action?docID=') === 0)
|| (strpos($dest_url, 'http://site.ebrary.com/lib/oculyork/Doc?id=') === 0)
|| (strpos($dest_url, 'https://site.ebrary.com/lib/oculyork/Doc?id=') === 0)
|| $_SERVER['REMOTE_ADDR'] == $ezproxy_ip
) {
  $logger->debug("Passing through to $dest_url");
  header("Location: $dest_url");
  exit;
}

?>

<?php include('template_top.php'); ?>


      <div class="row">
        <div class="col-lg-12">
          <div class="mt-4 card shadow-sm">
            <div class="card-body">
              <div class="text-center my-5">
                <h3>Please Login To Access Electronic Resources</h3>
              </div>
              <div class="row mb-5">
                <div class="col-sm-6">
                  <h5 class="border-bottom pb-2">Passport York</h5>
                  <p class="text-muted">
                    Current York University students, faculty, and staff will normally have a Passport York account and a library card (in the form of a YU-card for students and some employee groups) through which they will be authenticated as authorized York University users.
                  </p>
                  <p class="text-center mt-5">
                    <a href="passport?qurl=<?= urlencode($dest_url) ?>" class="btn btn-success btn-lg">
                    Login With Passport York
                    </a>
                  </p>
                </div>
                <div class="col-sm-5 offset-sm-1"">
                  <h5 class="border-bottom pb-2">  Library Barcode and Password</h5>
                  <p class="text-muted">
                    Other members of the York University community who do not have a Passport York account will need to authenticate using the barcode number on their library card and password.
                  </p>
                  <p class="text-center mt-5">
                    <a href="password?qurl=<?= urlencode($dest_url) ?>" class="btn btn-outline-info">
                    Login With Library Barcode
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>


<?php include('template_bottom.php'); ?>
