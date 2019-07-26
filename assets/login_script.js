window.addEventListener("load", function() {
  var urlParams = new URLSearchParams(window.location.search);
  const error_message = urlParams.has('error') ? urlParams.get('error') : '';
  if (error_message.length) {
    document.getElementById('loginform').innerHTML += "<div style='text-align:center; color:#c21c00'>" + error_message + "</div>"
  }
});

function showAdminFields(e) {
  document.querySelectorAll('label').forEach(l => l.classList.toggle('show'));
  document.querySelectorAll('p.submit').forEach(l => l.classList.toggle('show'));
  document.querySelectorAll('.mdd-login-widget').forEach(l => l.classList.toggle('hide'));
  document.querySelectorAll('#nav').forEach(l => l.classList.toggle('show'));
  document.querySelectorAll('#backtoblog').forEach(l => l.classList.toggle('show'));
  
  if (e.text.includes('Admin Login')) {
    e.text = 'MDD Login';
  } else if (e.text.includes('MDD Login')) {
    e.text = 'Admin Login';
  }
}
