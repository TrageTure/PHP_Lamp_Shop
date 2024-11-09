const colorRadios = document.querySelectorAll('input[name="color"]');
const sizeRadios = document.querySelectorAll('input[name="size"]');
const stockDisplay = document.getElementById('stock_display');
const priceDisplay = document.getElementById('price_display');
const add_to = document.getElementById('add');
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
    }
    else {
        if (e.target.classList.contains('plus')) {
            if (add_amount < maxStock) {
                console.log('add to cart');
                add_amount++;
                console.log(add_amount);
                amount_count.innerHTML = add_amount;
                error.style.display = 'none';
            }
            else {
                error.innerHTML = 'You can only add up to the available stock';
                error.style.display = 'block';
            }
        }
        else if (e.target.classList.contains('minus')) {
            console.log('remove from cart');
            if (add_amount > 0) {
                add_amount--;
                console.log(add_amount);
                amount_count.innerHTML = add_amount;
                error.style.display = 'none';
            }
        }
    }
});