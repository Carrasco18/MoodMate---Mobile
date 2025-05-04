// js/user_home.js
document.addEventListener('DOMContentLoaded', () => {
  console.log("user_home.js loaded");

  const modal       = document.getElementById('welcomeModal');
  const closeBtn    = modal.querySelector('.close-button');
  const contBtn     = document.getElementById('modalCloseBtn');
  const greetEl     = document.getElementById('greetingText');
  const encourageEl = document.getElementById('encourageText');
  const username    = modal.dataset.username;

  // Determine greeting
  const hour = new Date().getHours();
  let greeting = hour < 5      ? 'Hello' :
                 hour < 12     ? 'Good morning' :
                 hour < 17     ? 'Good afternoon' :
                 hour < 21     ? 'Good evening' : 'Hello';

  // Pick encouragement
  const phrases = [
    "You’re doing great today!",
    "Keep going—you’ve got this.",
    "Every small step counts.",
    "Believe in yourself.",
    "Stay strong and positive.",
    "Take a deep breath—you can do it.",
    "Your journey matters."
  ];
  const randomPhrase = phrases[Math.floor(Math.random() * phrases.length)];

  // Populate & show
  greetEl.textContent     = `${greeting}, ${username}!`;
  encourageEl.textContent = randomPhrase;
  modal.style.display     = 'flex';

  // Close handlers
  [closeBtn, contBtn].forEach(btn =>
    btn.addEventListener('click', () => modal.style.display = 'none')
  );

  // …rest of your home‑page JS…
});
