<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('structure/head.php'); ?>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <title>M&M Paraiso - Contact</title>


  <!--

TemplateMo 591 villa agency

https://templatemo.com/tm-591-villa-agency

-->
</head>

<?php
require_once 'config/config.php';

// Check if user is logged in and get their info
$userInfo = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("
            SELECT ua.email, up.first_name, up.last_name 
            FROM user_accounts ua 
            LEFT JOIN user_profiles up ON ua.user_id = up.user_id 
            WHERE ua.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $userInfo = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Failed to fetch user info: " . $e->getMessage());
    }
}

// Pre-fill form for logged in users
$contactName = '';
$contactEmail = '';
if ($userInfo) {
    $contactName = trim(($userInfo['first_name'] ?? '') . ' ' . ($userInfo['last_name'] ?? ''));
    $contactEmail = $userInfo['email'] ?? '';
}

// Fetch company information
$companyInfo = null;
try {
  $stmt = $pdo->prepare("SELECT * FROM company_information WHERE is_active = 1 LIMIT 1");
  $stmt->execute();
  $companyInfo = $stmt->fetch();
} catch (PDOException $e) {
  error_log("Failed to fetch company info: " . $e->getMessage());
}

// Use database values or fallback to defaults
$phone = $companyInfo['phone_number'] ?? '010-020-0340';
$email = $companyInfo['email'] ?? 'info@villa.co';
$googleMapsUrl = $companyInfo['google_maps_embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12469.776493332698!2d-80.14036379941481!3d25.907788681148624!2m3!1f357.26927939317244!2f20.870722720054623!3f0!3m2!1i1024!2i768!4f35!3m3!1m2!1s0x88d9add4b4ac788f%3A0xe77469d09480fcdb!2sSunny%20Isles%20Beach!5e1!3m2!1sen!2sth!4v1642869952544!5m2!1sen!2sth';
?>


<body>

  <!-- ***** Preloader Start ***** -->
  <?php include('structure/preloader.php'); ?>

  <!-- ***** Preloader End ***** -->

  <?php include('structure/subheader.php'); ?>


  <!-- ***** Header Area Start ***** -->
  <?php include('structure/header.php'); ?>

  <!-- ***** Header Area End ***** -->

  <div class="page-heading header-text">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <span class="breadcrumb"><a href="#">Home</a> / Contact Us</span>
          <h3>Contact Us</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="contact-page section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="section-heading">
            <h6>| Contact Us</h6>
            <h2>Get In Touch With Our Agents</h2>
          </div>
          <p>We'd love to hear from you! Whether you have a question about our properties, pricing, need assistance with booking, or anything else, our team is ready to answer all your questions.</p>
          <div class="row">
            <div class="col-lg-12">
              <div class="item phone">
                <img src="assets/images/phone-icon.png" alt="" style="max-width: 52px;">
                <h6><?php echo htmlspecialchars($phone); ?><br><span>Phone Number</span></h6>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="item email">
                <img src="assets/images/email-icon.png" alt="" style="max-width: 52px;">
                <h6><?php echo htmlspecialchars($email); ?><br><span>Business Email</span></h6>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <!-- Message container for AJAX responses -->
          <div id="formMessage"></div>

          <form id="contact-form">
            <div class="row">
              <div class="col-lg-12">
                <fieldset>
                  <label for="name">Full Name</label>
                  <input type="text" name="name" id="name" placeholder="Your Name..." autocomplete="on" required value="<?php echo htmlspecialchars($contactName); ?>">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="email">Email Address</label>
                  <input type="email" name="email" id="email" placeholder="Your E-mail..." required value="<?php echo htmlspecialchars($contactEmail); ?>">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="subject">Subject</label>
                  <input type="text" name="subject" id="subject" placeholder="Subject..." autocomplete="on">
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <label for="message">Message</label>
                  <textarea name="message" id="message" placeholder="Your Message" required></textarea>
                </fieldset>
              </div>
              <div class="col-lg-12">
                <fieldset>
                  <button type="submit" id="form-submit" class="orange-button">
                    <span id="btnText">Send Message</span>
                    <span id="btnSpinner" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Sending...</span>
                  </button>
                </fieldset>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-12">
          <div id="map">
            <iframe src="<?php echo htmlspecialchars($googleMapsUrl); ?>" width="100%" height="500px" frameborder="0" style="border:0; border-radius: 10px; box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.15);" allowfullscreen=""></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('structure/footer.php'); ?>


  <!-- Scripts -->
  <?php include('structure/scripts.php'); ?>

  <script>
  document.getElementById('contact-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const messageDiv = document.getElementById('formMessage');

    // Show loading state
    btnText.style.display = 'none';
    btnSpinner.style.display = 'inline';

    // Get form data
    const formData = {
      name: document.getElementById('name').value,
      email: document.getElementById('email').value,
      subject: document.getElementById('subject').value,
      message: document.getElementById('message').value
    };

    try {
      const res = await fetch('api/contact_inquiries.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
      });

      const data = await res.json();

      if (data.success) {
        messageDiv.innerHTML = '<div id="alertBox" class="alert alert-success mb-4 p-4 rounded bg-green-100 text-green-800">' + data.message + '</div>';
        // Clear subject and message fields, keep name and email for logged in users
        document.getElementById('subject').value = '';
        document.getElementById('message').value = '';
        // Auto-hide after 5 seconds
        setTimeout(() => {
          const alertBox = document.getElementById('alertBox');
          if (alertBox) alertBox.style.display = 'none';
        }, 5000);
      } else {
        messageDiv.innerHTML = '<div id="alertBox" class="alert alert-danger mb-4 p-4 rounded bg-red-100 text-red-800">' + (data.message || 'An error occurred') + '</div>';
        // Auto-hide after 5 seconds
        setTimeout(() => {
          const alertBox = document.getElementById('alertBox');
          if (alertBox) alertBox.style.display = 'none';
        }, 5000);
      }
    } catch (error) {
      console.error('Error:', error);
      messageDiv.innerHTML = '<div id="alertBox" class="alert alert-danger mb-4 p-4 rounded bg-red-100 text-red-800">Failed to send message. Please try again.</div>';
      // Auto-hide after 5 seconds
      setTimeout(() => {
        const alertBox = document.getElementById('alertBox');
        if (alertBox) alertBox.style.display = 'none';
      }, 5000);
    } finally {
      // Reset button state
      btnText.style.display = 'inline';
      btnSpinner.style.display = 'none';
    }
  });
  </script>

</body>

</html>