const colorRadios = document.querySelectorAll('input[name="color"]');
const sizeRadios = document.querySelectorAll('input[name="size"]');
const stockDisplay = document.getElementById('stock_display');
const priceDisplay = document.getElementById('price_display');
const add_to = document.querySelector('.container_price_cart');
const amount_count = document.querySelector('.amount_count');
const error = document.querySelector('.error');

let add_amount = 0;
let maxStock;

document.addEventListener('DOMContentLoaded', function() {
function updateStock() {
    const selectedColor = document.querySelector('input[name="color"]:checked');
    const selectedSize = document.querySelector('input[name="size"]:checked');

    if (selectedColor && selectedSize) {
        const colorId = selectedColor.value;
        const sizeId = selectedSize.value;

        fetch(`../process/get_stock.php?product_id=${productId}&color_id=${colorId}&size_id=${sizeId}`)
            .then(response => response.json())
            .then(data => {
                stockDisplay.textContent = `${data.stock_amount}`;
                priceDisplay.textContent = `$${data.price}`;
                maxStock = data.stock_amount; 

                add_amount = 0;
                amount_count.innerHTML = add_amount;
            })
            .catch(error => {
                console.error('Error fetching stock:', error);
                stockDisplay.textContent = 'Error fetching stock';
            });
    }
}

    colorRadios.forEach(radio => radio.addEventListener('change', updateStock));
    sizeRadios.forEach(radio => radio.addEventListener('change', updateStock));
});

add_to.addEventListener('click', (e) => {
    if (maxStock === undefined) {
        error.innerHTML = 'Please select a color and size';
        error.style.display = 'block';
    } else {
        // Controleer of de gebruiker op de plus-knop heeft geklikt
        if (e.target.classList.contains('plus')) {
            if (add_amount < maxStock) {
                add_amount++;
                amount_count.innerHTML = add_amount;
                error.style.display = 'none';
            } else {
                error.innerHTML = 'You can only add up to the available stock';
                error.style.display = 'block';
            }
        } 
        // Controleer of de gebruiker op de min-knop heeft geklikt
        else if (e.target.classList.contains('minus')) {
            if (add_amount > 0) {
                add_amount--;
                amount_count.innerHTML = add_amount;
                error.style.display = 'none';
            }
        }
    }
});

const addToCartButton = document.getElementById('add_to_cart_button');

addToCartButton.addEventListener('click', (e) => {
    const selectedColor = document.querySelector('input[name="color"]:checked');
    const selectedSize = document.querySelector('input[name="size"]:checked');

    if (selectedColor && selectedSize) {
        const colorId = selectedColor.value;
        const sizeId = selectedSize.value;

        fetch('../process/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                color_id: colorId,
                size_id: sizeId,
                amount: add_amount
            })
        })
        .then(response => response.text())
        .then(result => {
            console.log("Server response:", result);
            try {
                const data = JSON.parse(result);
                if (data.success) {
                    alert("Product added to cart!");
                    location.reload(); // Herlaad de pagina om de bijgewerkte winkelwagen te tonen
                } else {
                    error.innerHTML = data.message || 'Error adding to cart';
                    error.style.display = 'block';
                }
            } catch (e) {
                console.error("JSON parse error:", e);
                error.innerHTML = 'Invalid server response';
                error.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
        });
    }
});