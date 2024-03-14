<?php
// session_start();
// $data = "";
// $evtdata = "";
// $status = strip_tags(trim($_GET['Status']));
// $key = strip_tags(trim($_GET['Vkey']));

// if($status == 'SUCCESS'){
//   $vk = $_SESSION['AC_TKN']."_".$_SESSION['RF_TKN'];
//   // echo $key."|".$vk;
//   if(($key != $vk)){
//     $data = "";
//     die("Unathorized Access...Please check the link properly or visit admin");
//   }

//   $s = explode("_",$key);
//   $AT = $s[0];//accesstOken
//   $CT = $s[1];//RF TOKEN
 
//   if(($AT != $_SESSION['AC_TKN']) && ($CT != $_SESSION['RF_TKN']) && ($key != $vk)){
//     $data = "";
//     $evtdata = "";
//     die("Unathorized Access...Please check the link properly or visit admin");
//   }else{
//   $data = $_SESSION['name']." | ".$_SESSION['RegNo'];
//   $evtdata = $_SESSION['evoting_token'];

//   }
// }
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

<body >
  <!-- ======= Hero Section ======= -->
  <section id="hero" style="background-color: rgb(143, 141, 141);margin-top:0px!important;">
    <div class="hero-container  " data-aos="fade-in" style="height:500px;">
      <div class="row">
        <div class="col-md-12 col-xs-12 card" data-aos="fade-left" style="height:200px;width:500px;padding:20px">
            <h2 style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;color:red" ></h2>
            <h1 style="font-family: 'Arial Narrow';font-size: 30px;color:red" data-aos="fade-up">An error has occured,Please try again or contact the admin</h1>
      </div>
      </div>
      <br>
      
      
     
    </div>
  </section><!-- End Hero Section -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/js/jquery.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/script.js"></script>


</body>

</html>