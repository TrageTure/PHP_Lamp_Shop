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
    if (editMode === true) {
        deleteButtons.forEach(button => {
            button.style.display = 'none';
        });
    } else {
        deleteButtons.forEach(button => {
            button.style.display = 'block';
        });
    }
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
    const nameText = address.querySelector('.adress_name').textContent;
    const streetText = address.querySelectorAll('p')[1].textContent;
    const cityText = address.querySelectorAll('p')[2].textContent;
    const countryText = address.querySelectorAll('p')[3].textContent;

    address.innerHTML = `
        <input type="text" class="name-input" value="${nameText}">
        <input type="text" class="street-input" value="${streetText}">
        <input type="text" class="city-input" value="${cityText}">
        <input type="text" class="country-input" value="${countryText}">
        <button class="save-button">Save</button>
    `;

    const saveButton = address.querySelector('.save-button');
    saveButton.addEventListener('click', () => {
        saveAddressChanges(address);
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
            console.log('Server response:', result);
            if (result.success) {
                console.log('Address added:', result.address);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
});
