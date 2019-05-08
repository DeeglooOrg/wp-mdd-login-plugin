<h1>Unauthorized access</h1>
<div>
Only CDI users can login with MDD SSO login feature. Redirecting to login page in 3 seconds...
</div>
<script>
  window.addEventListener("load", function() {
    setTimeout(() => {
      window.location = '<?php echo wp_login_url(); ?>';
    }, 3000);
  });
</script>

