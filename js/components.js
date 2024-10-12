const shopping_cart = document.querySelector('.shopping_cart_flex');
const shopping_cart_items = document.querySelector('.shopping_cart_items');
const shopping_cart_indicator = document.querySelector('.indicator')
var shopping_cart_state = false
console.log("hallo")

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
