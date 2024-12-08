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
                priceDisplay.textContent = `€${data.price}`;
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

const review_background = document.getElementById('background');
const review_button = document.getElementById('review_btn');
const review_form = document.getElementById('reviewForm');

review_button.addEventListener('click', (e) => {
    review_background.style.display = 'block';
});

const close_review = document.getElementById('close');

close_review.addEventListener('click', (e) => {
    review_background.style.display = 'none';
});

const error_rev = document.querySelector('.error');

review_form.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(review_form);

    fetch('../process/add_review.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(result => {
            console.log("Server response:", result);
            try {
                const data = JSON.parse(result);
                if (data.success) {
                    review_background.style.display = 'none';

                    const reviewList = document.getElementById('reviews_grid');

                    // Maak een nieuw review-element
                    const newReview = document.createElement('section');
                    newReview.classList.add('review_container');
                    newReview.innerHTML = `
                        <div class="review">
                            <div class="flex_review">
                                <img src="../images/pf_pics${data.profile_pic}" alt="profile_pic_review">
                                <div id="right">
                                    <div class="flex_name_stars">
                                        <h3>${data.user_name || 'Anonieme gebruiker'}</h3>
                                        <div class="stars">
                                            ${Array.from({ length: 5 }, (_, i) => 
                                                `<div class="star ${i < data.rating ? 'filled' : 'empty'}"></div>`
                                            ).join('')}
                                        </div>
                                    </div>
                                    <p>${data.review || ''}</p>
                                </div>
                            </div>
                        </div>`;
                    // Voeg de nieuwe review toe aan de reviews-grid
                    reviewList.prepend(newReview);

                    // Reset het formulier
                    review_form.reset();
                } else {
                    error_rev.innerHTML = data.message || 'Error adding review';
                    error_rev.style.display = 'block';
                }
            } catch (e) {
                console.error("JSON parse error:", e);
                error_rev.innerHTML = 'Invalid server response';
                error_rev.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error adding review:', error);
        });
});

document.getElementById('loadMoreReviews').addEventListener('click', function() {
    const button = this;
    const productId = button.getAttribute('data-product_id');
    console.log(productId);
    const offset = parseInt(button.getAttribute('data-offset'));
    const limit = parseInt(button.getAttribute('data-limit'));

    fetch('../process/fetch_reviews.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            limit: limit,
            offset: offset,
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const reviewsGrid = document.getElementById('reviews_grid');
                data.reviews.forEach(review => {
                    const reviewElement = document.createElement('section');
                    reviewElement.classList.add('review_container');
                    reviewElement.innerHTML = `
                        <div class="review">
                            <div class="flex_review">
                                <img src="../images/pf_pics/${review.profile_pic}" alt="profile_pic_review">
                                <div id="right">
                                    <div class="flex_name_stars">
                                        <h3>${review.first_name} ${review.last_name}</h3>
                                        <div class="stars">
                                            ${Array.from({ length: 5 }, (_, i) => 
                                                `<div class="star ${i < review.stars_amount ? 'filled' : ''}"></div>`
                                            ).join('')}
                                        </div>
                                    </div>
                                    <p>${review.review}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    reviewsGrid.appendChild(reviewElement);
                });

                // Update the offset
                button.setAttribute('data-offset', offset + limit);

                if (data.reviews.length < limit) {
                    button.disabled = true;
                    button.textContent = 'Geen meer reviews';
                }
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
});