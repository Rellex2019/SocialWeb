const customSelect = document.getElementById('customSelect');
const optionList = document.getElementById('optionList');
const optionItems = document.querySelectorAll('.option-item');

customSelect.addEventListener('click', () => {
    const isVisible = optionList.style.display === 'block';
    optionList.style.display = isVisible ? 'none' : 'block';
});

optionItems.forEach(item => {
    item.addEventListener('click', (event) => {
        const selectedValue = event.target.textContent;
        customSelect.textContent = selectedValue + ' ';
        optionList.style.display = 'none';
    });
});

// Закрыть список, если кликнули вне его
window.addEventListener('click', (event) => {
    if (!customSelect.contains(event.target) && !optionList.contains(event.target)) {
        optionList.style.display = 'none';
    }
});


function openMenu() {
    document.getElementById("overlay").style.display = "block";
    document.getElementById("menu").style.display = "block";
}

function closeMenu() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("menu").style.display = "none";
}



