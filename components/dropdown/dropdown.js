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
            dropdownContent.innerHTML = '';
            data.forEach(item => {
                var selection = document.createElement('div');
                selection.addEventListener('mousedown', SelectOption)
                selection.className = 'dropdown-option';
                selection.textContent = item;
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
    var input = this.parentNode.parentNode.querySelectorAll('.searchInput')[0]
    input.value = this.innerHTML
}