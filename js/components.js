const shopping_cart = document.querySelector('.shopping_cart_flex');
const shopping_cart_items = document.querySelector('.shopping_cart_items');
const shopping_cart_indicator = document.querySelector('.indicator')
var shopping_cart_state = false

shopping_cart.addEventListener("click", () => {
    if (shopping_cart_state === false) {
        shopping_cart_items.classList.add('show');
        shopping_cart_indicator.style.transform = "rotate(0deg)"
        shopping_cart_state = true;
    }
    else {
        shopping_cart_items.classList.remove('show');
        shopping_cart_indicator.style.transform ="rotate(180deg)"
        shopping_cart_state = false;
    }
    console.log(shopping_cart_state)
})

document.addEventListener('DOMContentLoaded', function() {
    // Selecteer alle verwijder-knoppen in de winkelwagen
    const deleteButtons = document.querySelectorAll('.delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemIndex = this.dataset.index; // Haal het indexnummer van het item op
            fetch(`../process/delete_from_cart.php?index=${itemIndex}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Item verwijderd uit winkelwagen!');
                    location.reload(); // Herlaad de pagina om de bijgewerkte winkelwagen te tonen
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
