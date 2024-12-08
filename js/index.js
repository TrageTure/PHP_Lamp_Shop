const search = document.getElementById('search');

search.addEventListener('keyup', (e) => {
    const userText = search.value;

    let newFormData = new FormData();
    newFormData.append('search', userText);

    fetch('../process/search.php', {
        method: 'POST',
        body: newFormData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const outputElement = document.querySelector('.products');
                outputElement.innerHTML = ''; // Leegmaken voor nieuwe resultaten
                
                // Itereer door de producten en genereer HTML
                data.products.forEach((product) => {
                    const article = document.createElement('article');
                    article.onclick = () => {
                        window.location.href = `product_details.php?id=${product.id}`;
                    };

                    article.innerHTML = `
                        <img src='../images/product_images/${product.thumbnail}' class='product_img'>
                        <div class="info_container">
                            <h1 class="name">${product.title}</h1>
                            <h2 class="price">€${product.min_price}</h2>
                            <p class="description">${product.description}</p>
                        </div>
                    `;
                    outputElement.appendChild(article);
                });
            } else {
                console.log(data.message);
            }
        })
        .catch((error) => console.error(error));
});