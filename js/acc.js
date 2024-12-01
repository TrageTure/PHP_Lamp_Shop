// Elements for the "Add Address" modal
const addAdress = document.getElementById('add_adress'); 
const background = document.getElementById('background');

// Open the "Add Address" modal
addAdress.addEventListener('click', () => {
    background.classList.remove('hidden');
});

// Close buttons for the "Add Address" modal
const closeButtons = document.querySelectorAll('.close');

closeButtons.forEach(button => {
    button.addEventListener('click', () => {
        background.classList.add('hidden');
    });
});

const editButton = document.getElementById('editButton');
const addresses = document.querySelectorAll('.adress_grid');

let editMode = false;
let isEditing = false;

const deleteButtons = document.querySelectorAll('.delete_adress');


editButton.addEventListener('click', () => {
    console.log(`isEditing = ${isEditing}`)
    if (editMode === false && isEditing === false) {
        editMode = true;
        console.log('Edit mode is aan');
        addresses.forEach(address => {
            address.classList.add('edit');
        });
    }
    else {
        editMode = false;
        console.log('Edit mode is uit');
        addresses.forEach(address => {
            address.classList.remove('edit');
        });
    }
});

addresses.forEach(address => {
    address.addEventListener('click', () => {
        if (!editMode || isEditing) {
            return;
        }

        isEditing = true; 
        addresses.forEach(address => {
            address.classList.remove('edit');
        });

        if (editMode) {
            makeAddressEditable(address);
            editMode = false;
        }
    });
});

function makeAddressEditable(address) {
    // Haal de gegevens op uit de HTML-structuur
    const nameText = address.querySelector('.adress_name').textContent;
    const streetText = address.querySelector('.street_name').textContent;
    const houseNumberText = address.querySelector('.house_number').textContent;
    const postalCodeText = address.querySelector('.postal_code').textContent;
    const cityText = address.querySelector('.city').textContent;
    const countryText = address.querySelector('p:last-of-type').textContent; // Laatste <p> bevat het land
    const id = address.querySelector('.delete_adress').getAttribute('data-id');

    // Verander de HTML in een formulier om de waarden aan te passen
    address.innerHTML = `
        <form class="edit-address-form">
            <div class="delete_adress" data-id="${id}"></div>
            <input type="text" name="name" class="name-input" value="${nameText}">
            <input type="text" name="street" class="street-input" value="${streetText}">
            <input type="text" name="house_number" class="house-number-input" value="${houseNumberText}">
            <input type="text" name="postal_code" class="postal-code-input" value="${postalCodeText}">
            <input type="text" name="city" class="city-input" value="${cityText}">
            <input type="text" name="country" class="country-input" value="${countryText}">
            <button type="submit" class="save-button">Save</button>
        </form>
    `;

    const editAddressForm = address.querySelector('.edit-address-form');
    editAddressForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(editAddressForm);
        const deleteButton = address.querySelector('.delete_adress');
        const addressId = deleteButton.getAttribute('data-id');

        formData.append('id', addressId);

        fetch('../process/update_adress.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.status === 'success') {
                    // Werk de HTML bij met de nieuwe waarden
                    address.innerHTML = `
                        <div class="delete_adress" data-id="${addressId}"></div>
                        <p class="adress_name">${formData.get('name')}</p>
                        <p><span class="street_name">${formData.get('street')}</span> <span class="house_number">${formData.get('house_number')}</span></p>
                        <p><span class="postal_code">${formData.get('postal_code')}</span> <span class="city">${formData.get('city')}</span></p>
                        <p>${formData.get('country')}</p>
                    `;
                    isEditing = false;
                    console.log(result.message); // Debug
                } else {
                    alert(result.message);
                }
            })
            .catch(error => {
                console.error('Fout bij het updaten:', error);
                alert('Er is een fout opgetreden. Probeer het opnieuw.');
            });
    });
}

function saveAddressChanges(address) {
    const nameValue = address.querySelector('.name-input').value;
    const streetValue = address.querySelector('.street-input').value;
    const cityValue = address.querySelector('.city-input').value;
    const countryValue = address.querySelector('.country-input').value;

    address.innerHTML = `
        <div class="delete_adress"></div>
        <p class="adress_name">${nameValue}</p>
        <p>${streetValue}</p>
        <p>${cityValue}</p>
        <p>${countryValue}</p>
    `;

    isEditing = false;
    console.log('Address updated:', { nameValue, streetValue, cityValue, countryValue, isEditing });
}

document.getElementById('addressForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(document.getElementById('addressForm'));

    fetch('../process/add_adress.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                location.reload();
                background.classList.add('hidden');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});

deleteButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        const addressId = e.target.getAttribute('data-id');
        console.log(addressId);
        let data = new FormData();
        data.append('addressId', addressId);
        fetch('../process/delete_adress.php', {
            method: 'POST',
            body: data,
        })
            .then(response => response.json())
            .then(result => {
                const addressElement = document.querySelector(`.delete_adress[data-id="${addressId}"]`).parentElement;
                console.log('Gevonden element:', addressElement);
                const adress_divider = document.querySelector(`.divider_adress[data-id="${addressId}"]`);

                addressElement.remove();
                adress_divider.remove();
                if (result.success) {
                    console.log('Address deleted:', result.address);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
});

//choosing active adress
// Actief adres kiezen
const activeButton = document.getElementById('choose_btn');
const chosenAddressContainer = document.querySelector('.adress_grid_chosen');
let choosing_mode = false;

activeButton.addEventListener('click', () => {
    if (choosing_mode) {
        addresses.forEach(address => {
            address.classList.remove('edit');
            address.removeEventListener('click', chooseAddressHandler);
        });
        choosing_mode = false;
    } else {
        addresses.forEach(address => {
            address.classList.add('edit');
            address.addEventListener('click', chooseAddressHandler);
        });
        choosing_mode = true;
    }
});

function chooseAddressHandler(event) {
    addresses.forEach(address => {
        address.classList.remove('edit');
    });

    const addressId = event.currentTarget.querySelector('.delete_adress').getAttribute('data-id');
    let data = new FormData();
    data.append('id', addressId);

    fetch('../process/choose_adress.php', {
        method: 'POST',
        body: data,
    })
    .then(response => response.json())
    .then(result => {
        console.log(result);
        if (result.status === 'success') {
            location.reload();
        }
    });
}

//ajax voor aanpassen wachtwoord
const button = document.getElementById('edit_ww');
// const passwordForm = document.getElementById('password-modal');
const passwordBackground = document.getElementById('bckgrnd_password');

button.addEventListener('click', () => {
    passwordBackground.classList.remove('hidden');
});

const closePassword = document.getElementById('cancel-password');

closePassword.addEventListener('click', () => {
    passwordBackground.classList.add('hidden');
    console.log('closed');
});

const passwordForm = document.getElementById('passwordForm');
const error = document.getElementById('error');

passwordForm.addEventListener('submit', (e) => {
    console.log(e.target.value);
    e.preventDefault();

    const formData = new FormData(passwordForm);

    fetch('../process/change_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            // location.reload();
            passwordBackground.classList.add('hidden');
        }
        else {
            const errorDiv = document.getElementById(`${result.error_div}`);
            error.textContent = result.message;
            error.style.display = 'block';
            errorDiv.style.border = '3px solid red';
            setTimeout(() => {
                error.style.display = 'none';
                errorDiv.style.border = '#415f94 3px solid';
            }, 3000);   
            console.log(result.error_div);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
);