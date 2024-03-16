<?php
session_start();
error_reporting(0);
$data = "";

if(!isset($_GET['Status']) || !isset($_GET['Vkey'])){

  die("Unathorized Access...Direct access is not allowed");
}else{
    $status = strip_tags(trim($_GET['Status']));
    $key = strip_tags(trim($_GET['Vkey']));
    if($status == 'SUCCESS'){
      $vk = $_SESSION['AC_TKN']."_".$_SESSION['CSRF_TKN'];
      // echo $key."|".$vk;
      if(($key != $vk)){
        $data = "";
        die("Unathorized Access...Please check the link properly or visit admin");
      }

      $s = explode("_",$key);
      $AT = $s[0];//accesstOken
      $CT = $s[1];//CRSF TOKEN
    
      if(($AT != $_SESSION['AC_TKN']) && ($CT != $_SESSION['CSRF_TKN']) && ($key != $vk)){
        $data = "";
        die("Unathorized Access...Please check the link properly or visit admin");
      }else{
      $data = $_SESSION['RegNo'];
      }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>e-Vault</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Roboto:100,300,400,500,700|Philosopher:400,400i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body style="">
  <!-- ======= Hero Section ======= -->
  <section id="hero" style="background-color: rgb(143, 141, 141);margin-top:0px!important;">
    <div class="hero-container " data-aos="fade-in" style="height:650px;">
      <h1>Welcome to the e-vault</h1>
      <h2 style="color:#fff">Secured Access | Encrypted Information | Web3 Enabled | Access Control</h2>
      <img src="assets/img/vaultt.jpg" alt="Hero Imgs" data-aos="zoom-out" data-aos-delay="100">
      
      <form id="pass-form" class="form-inline">
        <div class="form-group" >
        <label for="pass" style="color:white;font-weight:bolder"><?= $data;?> </label>
        <input type="password" name="pass_word" class="form-control" style="width: 100%;" id="pass" placeholder="Enter your vault password">
        <input type="hidden" name="crsf_token" value="<?= $_SESSION['CSRF_TKN'];?>" class="form-control" style="width: 100%;" id="crsf_token" >
       </div><br>
       <button type="submit" class="btn btn-dark">Submit</button>
      </form>
  <span id="stg"></span>
    </div>
  </section><!-- End Hero Section -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
  <!-- Template Main JS File -->
   <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/script.js"></script>
 

</body>

</html>