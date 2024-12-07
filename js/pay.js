const paymentForm = document.getElementById('paymentForm');
const feedback_order = document.getElementById('feedback_order');
const feedback_title = document.getElementById('feedback_title');
const feedback_message = document.getElementById('feedback_message');

paymentForm.addEventListener('submit', (e) => {
    e.preventDefault();
    fetch('../process/place_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                //pagina herlaad
                feedback_order.style.display = 'block';
                feedback_order.style.border = '3px solid green';
                feedback_title.innerHTML = 'Bestelling geplaatst';
                feedback_message.innerHTML = 'Bedankt voor uw bestelling. U kunt uw bestellingen terug zien bij je profiel.';
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 3000);
            } else {
                feedback_order.style.display = 'block';
                feedback_order.style.border = '3px solid red';
                feedback_title.innerHTML = 'Bestelling niet geplaatst';
                feedback_message.innerHTML = data.message;
                setTimeout(() => {
                    feedback_order.style.display = 'none';
                }, 3000);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});