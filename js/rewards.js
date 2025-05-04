// rewards.js
document.addEventListener('DOMContentLoaded', () => {
  const grid    = document.getElementById('rewardsGrid');
  const claimBtn = document.getElementById('claimBtn');
  const msgEl   = document.getElementById('message');
  let streakDay, canClaim;

  // Fetch status from server
  fetch('daily_status.php')
    .then(r => r.json())
    .then(data => {
      streakDay = data.streakDay;
      canClaim  = data.canClaim;

      // Build 7 cells
      grid.innerHTML = '';
      data.rewards.forEach((rw, i) => {
        const cell = document.createElement('div');
        cell.className = 'reward-cell ' + (
          i < streakDay   ? 'claimed' :
          i === streakDay && canClaim ? 'available' :
          'locked'
        );
        cell.innerHTML = `
          <i class="fas ${rw.icon}"></i>
          <span>${rw.text}</span>
        `;
        grid.appendChild(cell);
      });

      // Enable claim button?
      claimBtn.disabled = !canClaim;
    });

  // Handle claim
  claimBtn.addEventListener('click', () => {
    fetch('claim_reward.php', { method: 'POST' })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          msgEl.textContent = 'Reward claimed! Come back tomorrow for the next one.';
          claimBtn.disabled = true;
          // Refresh grid
          setTimeout(() => location.reload(), 1000);
        } else {
          msgEl.textContent = res.message || 'Unable to claim right now.';
        }
      });
  });
});
