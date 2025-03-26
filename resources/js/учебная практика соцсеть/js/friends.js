function showContent(section) {
    const applicantsBtn = document.getElementById('applicantsBtn');
    const completedBtn = document.getElementById('completedBtn');
    const applicantsContent = document.getElementById('applicantsContent');
    const completedContent = document.getElementById('completedContent');

    if (section === 'applicants') {
        applicantsContent.style.display = 'block';
        completedContent.style.display = 'none';
        applicantsBtn.classList.add('active');
        completedBtn.classList.remove('active');
    } else if (section === 'completed') {
        completedContent.style.display = 'block';
        applicantsContent.style.display = 'none';
        completedBtn.classList.add('active');
        applicantsBtn.classList.remove('active');
    }
}

// Показываем раздел "Заявки" по умолчанию при загрузке страницы
showContent('applicants');


const searchInput = document.getElementById('searchInput');

searchInput.addEventListener('focus', function () {
    this.placeholder = ''; // Убираем placeholder
    this.value = ''; // Очищаем поле при фокусе
});