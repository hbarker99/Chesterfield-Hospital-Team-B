document.querySelectorAll('.searchInput').forEach(input => {
    input.addEventListener('keyup', Search);
    input.addEventListener('focus', Search);
    input.addEventListener('focusout', Close);
    input.addEventListener('input', HandleClearButton);
});

document.querySelectorAll('.clear-button').forEach(clearButton => {
    clearButton.addEventListener('click', ClearInput);
})

function Search(e) {
    var searchValue = e.type === 'keyup' ? this.value : '';
    var dropdownContent = this.parentNode.querySelector('.dropdown-content');
    fetch('components/dropdown/data.php?search=' + searchValue)
        .then(response =>  response.json())
        .then(data => {
            dropdownContent.innerHTML = '';
            data.forEach(item => {
                var selection = document.createElement('div');
                selection.addEventListener('mousedown', SelectOption)
                selection.className = 'dropdown-option';

                selection.textContent = item.name;
                selection.id = item.node_id;
                dropdownContent.appendChild(selection);
            });
            dropdownContent.style.display = 'flex';
        })
        .catch(error => console.error('Error fetching data:', error));
}

function Close(e) {
    var dropdownContent = this.parentNode.querySelector('.dropdown-content');
    dropdownContent.style.display = 'none';
}

function SelectOption() {
    // Find the search input associated with this dropdown
    var input = this.closest('.dropdown').querySelector('.searchInput');
    
    // Find the ID of the hidden input field associated with this dropdown
    var dropdownId = input.id;
    var inputId = document.getElementById(dropdownId == '1' ? 'startPoint' : 'endPoint');
    
    // Update the value of the search input and the hidden input field
    input.value = this.textContent; // Update the visible input with the selected name
    inputId.value = this.id; // Update the hidden input with the selected node_id

    AddClearButton(this.parentNode.parentNode.querySelector('.clear-button'));
}

function HandleClearButton() {
    var hasValue = !!this.value;

    if (hasValue) {
        AddClearButton(this.parentNode.querySelector('.clear-button'));
    } else {
        RemoveClearButton(this.parentNode.querySelector('.clear-button'));
    }
}

function AddClearButton(clearButton) {


    if (clearButton.classList.contains("visible"))
        return;

    clearButton.classList.add("visible")
}

function RemoveClearButton(clearButton) {
    if (!clearButton.classList.contains("visible"))
        return;

    clearButton.classList.remove("visible")
}

function ClearInput() {
    this.parentNode.querySelector('.searchInput').value = '';
    RemoveClearButton(this.parentNode.querySelector('.clear-button'));
}