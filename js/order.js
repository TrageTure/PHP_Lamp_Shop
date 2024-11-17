const deliveryOptions = document.querySelectorAll('input[name="delivery"]');
const plus = document.querySelectorAll('.plus');
const minus = document.querySelectorAll('.minus');
const quantity = document.querySelectorAll('.amount_count');
let selectedDelivery;

function updateDelivery() {
    selectedDelivery = document.querySelector('input[name="delivery"]:checked');

    if (selectedDelivery) {
        const deliveryId = selectedDelivery.value;

        fetch(`../process/set_delivery.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ delivery_id: deliveryId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Aflevermethode succesvol opgeslagen');
                if (selectedDelivery.value === "standard") {
                    document.querySelector('.delivery_price').innerHTML = "€0";
                    document.querySelector('.delivery_type').innerHTML = "Standard";
                }   
                else if (selectedDelivery.value === "express") {
                    document.querySelector('.delivery_price').innerHTML = "€7.99";
                    document.querySelector('.delivery_type').innerHTML = "Express";
                }
                const productPrice = parseFloat(document.querySelector('.product_price').innerText.replace('€', ''));
                const deliveryPrice = selectedDelivery.value === "express" ? 7.99 : 0;
                const totalPrice = productPrice + deliveryPrice;
                document.querySelector('.total_price').innerHTML = `€${totalPrice.toFixed(2)}`;
            } else {
                console.error('Fout bij het opslaan van de aflevermethode:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

deliveryOptions.forEach(option => {
    option.addEventListener('change', updateDelivery);
});

document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemIndex = this.dataset.index;
            fetch(`../process/delete_from_cart.php?index=${itemIndex}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Item verwijderd uit winkelwagen!');
                    location.reload(); 
                } else {
                    alert('Er is iets misgegaan bij het verwijderen van het item.');
                }
            })
            .catch(error => {
                console.error('Fout bij het verwijderen van het item:', error);
            });
        });
    });
});