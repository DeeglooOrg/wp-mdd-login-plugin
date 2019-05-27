function showAdminFields(e) {
  document.querySelectorAll('label').forEach(l => l.classList.toggle('show'));
  document.querySelectorAll('p.submit').forEach(l => l.classList.toggle('show'));
  document.querySelectorAll('.mdd-login-widget').forEach(l => l.classList.toggle('hide'));
  if (e.text.includes('Admin Login')) {
    e.text = 'MDD Login';
  } else if (e.text.includes('MDD Login')) {
    e.text = 'Admin Login';
  }
}
