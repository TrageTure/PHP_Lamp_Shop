const deliveryOptions = document.querySelectorAll('input[name="delivery"]');
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
    const plus = document.querySelectorAll('.plus');
    const minus = document.querySelectorAll('.minus');
    const quantity = document.querySelectorAll('.amount_count');

    // Verwijder item uit de winkelwagen
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemIndex = this.dataset.index;
            fetch('../process/delete_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ index: itemIndex })
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

    // Verhoog het aantal van een item in de winkelwagen
    plus.forEach(button => {
        button.addEventListener('click', function () {
            console.log('clicked');
            const itemIndex = this.closest('.order_item').querySelector('.order_delete').dataset.index;
            fetch('../process/update_quantity_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ index: itemIndex, action: 'increase' })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload(); 
                } else {
                    alert(result.message);
                }
            })
            .catch(error => {
                console.error('Fout bij het verhogen van de hoeveelheid:', error);
            });
        });
    });

    // Verlaag het aantal van een item in de winkelwagen
    minus.forEach(button => {
        button.addEventListener('click', function() {
            const itemIndex = this.closest('.order_item').querySelector('.order_delete').dataset.index;
            fetch('../process/update_quantity_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ index: itemIndex, action: 'decrease' })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload(); 
                } else {
                    alert(result.message);
                }
            })
            .catch(error => {
                console.error('Fout bij het verlagen van de hoeveelheid:', error);
            });
        });
    });

});

const btn_bestellen = document.querySelector('.btn_bestellen');
btn_bestellen.addEventListener('click', function() {
    fetch('../process/place_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Bestelling geplaatst!');
            location.href = '../index.php';
        } else {
            alert(result.message);
        }
    })
    .catch(error => {
        console.error('Fout bij het plaatsen van de bestelling:', error);
    });
});