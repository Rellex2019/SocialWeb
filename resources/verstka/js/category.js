document.addEventListener('DOMContentLoaded', () => {
    const categoryButtonsContainer = document.getElementById('categoryButtons');
    const contentText = document.getElementById('contentText');

    // Пример данных категорий (можно заменить на AJAX запрос к базе данных)
    const categories = [
        { id: 1, name: 'музыка', content: ''},
        { id: 2, name: 'рисунки', content: 'Содержимое для категории 2.' },
        { id: 3, name: 'интернет', content: 'Содержимое для категории 3.' },
    ];

    // Генерация кнопок категорий
    categories.forEach(category => {
        const button = document.createElement('button');
        button.innerText = category.name;
        button.className = 'category-button';
        button.dataset.id = category.id;
        
        button.addEventListener('click', () => {
            // Удаляем активный класс у всех кнопок
            document.querySelectorAll('.category-button').forEach(btn => btn.classList.remove('active'));
            // Добавляем активный класс к текущей кнопке
            button.classList.add('active');
            // Меняем содержимое
            contentText.innerText = category.content;
        });

        categoryButtonsContainer.appendChild(button);
    });
});
