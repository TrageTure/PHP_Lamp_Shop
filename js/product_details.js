const colorRadios = document.querySelectorAll('input[name="color"]');
const sizeRadios = document.querySelectorAll('input[name="size"]');
const stockDisplay = document.getElementById('stock_display');
const priceDisplay = document.getElementById('price_display');
const add_to = document.getElementById('add');

let add_amount = 0;

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
    console.log(e.target);
    if (e.target.classList.contains('plus')) {
        console.log('add to cart');
        add_amount++;
        console.log(add_amount);
    }
    else if (e.target.classList.contains('minus')) {
        console.log('remove from cart');
        if (add_amount > 0) {
            add_amount--;
            console.log(add_amount);
        }
    }
});