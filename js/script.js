// Basic client‑side password length check
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  if (!form) return;

  form.addEventListener('submit', e => {
    const pw = form.querySelector('input[name="password"]');
    if (pw && pw.value.length < 6) {
      alert('Password must be at least 6 characters.');
      e.preventDefault();
    }
  });
});
