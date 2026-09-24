document.addEventListener('DOMContentLoaded', function() {
  const starButtons = document.querySelectorAll('.star-btn');
  const btnGoogle = document.getElementById('btnGoogle');
  const btnFormulario = document.getElementById('btnFormulario');
  const starFeedback = document.getElementById('starFeedback');
  let selectedRating = 0;

  const feedbackMessages = {
    1: 'Lamentamos que tu experiencia no haya sido buena',
    2: 'Gracias por tu honestidad, queremos mejorar.',
    3: 'Gracias por tu calificación neutral.',
    4: 'Nos alegra que hayas tenido una buena experiencia!',
    5: 'Excelente! Nos alegra mucho que estés satisfecho!'
  };

  starButtons.forEach((btn) => {
    const rating = parseInt(btn.dataset.value);

    btn.addEventListener('mouseenter', function() {
      highlightStars(rating, false);
    });

    btn.addEventListener('mouseleave', function() {
      highlightStars(selectedRating, false);
    });

    btn.addEventListener('click', function() {
      selectRating(rating);
    });
  });

  function highlightStars(rating, isSelected) {
    starButtons.forEach((btn) => {
      const btnRating = parseInt(btn.dataset.value);
      const icon = btn.querySelector('i');

      if (btnRating <= rating) {
        icon.classList.add('filled');
        btn.classList.add('active');
      } else {
        icon.classList.remove('filled');
        btn.classList.remove('active');
      }
    });
  }

  function selectRating(rating) {
    selectedRating = rating;
    
    starButtons.forEach((btn) => {
      btn.classList.remove('selected');
    });

    highlightStars(rating, true);
    starButtons[rating - 1].classList.add('selected');
    
    starFeedback.textContent = feedbackMessages[rating] || '';
    starFeedback.style.color = getFeedbackColor(rating);

    showActionButtons(rating);
  }

  function getFeedbackColor(rating) {
    if (rating >= 4) return 'var(--color-exito)';
    if (rating === 3) return 'var(--color-acento)';
    return 'var(--color-error)';
  }

  function showActionButtons(rating) {
    setTimeout(() => {
      if (rating >= 3) {
        btnGoogle.classList.remove('btn-hidden');
        btnGoogle.classList.add('btn-visible');
        btnFormulario.classList.remove('btn-visible');
        btnFormulario.classList.add('btn-hidden');
      } else {
        btnFormulario.classList.remove('btn-hidden');
        btnFormulario.classList.add('btn-visible');
        btnGoogle.classList.remove('btn-visible');
        btnGoogle.classList.add('btn-hidden');
      }
    }, 300);
  }
});
