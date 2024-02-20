document.querySelectorAll('.searchInput').forEach(input => {
    input.addEventListener('keyup', Search);
    input.addEventListener('focus', Search);
    input.addEventListener('focusout', Close);
});

function Search(e) {
    var searchValue = e.type === 'keyup' ? this.value : '';
    var dropdownContent = this.parentNode.querySelector('.dropdown-content');
    fetch('components/dropdown/data.php?search=' + searchValue)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            dropdownContent.innerHTML = '';
            data.forEach(item => {
                console.log(item)
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
    var input = this.parentNode.parentNode.querySelector('.searchInput')
    var inputId = this.parentNode.parentNode.querySelector('#dropdownValue')

    input.value = this.innerHTML
    inputId.value = this.id
}