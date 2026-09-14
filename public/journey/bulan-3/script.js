// Memilih elemen DOM yang dibutuhkan
const todoForm = document.getElementById('todo-form');
const todoInput = document.getElementById('todo-input');
const todoList = document.getElementById('todo-list');
const itemsLeft = document.getElementById('items-left');
const clearCompletedBtn = document.getElementById('clear-completed');
const filterBtns = document.querySelectorAll('.filter-btn');

// State aplikasi (mengambil dari Local Storage jika ada)
let todos = [];
try {
    const storedTodos = JSON.parse(localStorage.getItem('journey:todos'));
    todos = Array.isArray(storedTodos)
        ? storedTodos.filter(todo => todo && typeof todo.id === 'string' && typeof todo.text === 'string')
            .slice(-200)
            .map(todo => ({ id: todo.id, text: todo.text.slice(0, 200), completed: Boolean(todo.completed) }))
        : [];
} catch {
    todos = [];
}
let currentFilter = 'all';

// Fungsi untuk menyimpan data ke Local Storage
function saveToLocalStorage() {
    localStorage.setItem('journey:todos', JSON.stringify(todos));
}

// Fungsi untuk merender daftar To-Do
function renderTodos() {
    todoList.innerHTML = '';

    // Filter array berdasarkan status saat ini
    let filteredTodos = todos;
    if (currentFilter === 'pending') {
        filteredTodos = todos.filter(todo => !todo.completed);
    } else if (currentFilter === 'completed') {
        filteredTodos = todos.filter(todo => todo.completed);
    }

    if (filteredTodos.length === 0) {
        todoList.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p>Tidak ada tugas di sini.</p>
            </div>
        `;
    } else {
        filteredTodos.forEach((todo) => {
            const li = document.createElement('li');
            li.className = `todo-item ${todo.completed ? 'completed' : ''}`;
            const content = document.createElement('div');
            content.className = 'todo-content';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'todo-checkbox';
            checkbox.checked = todo.completed;
            checkbox.dataset.id = todo.id;
            checkbox.setAttribute('aria-label', `Tandai ${todo.text} selesai`);
            const text = document.createElement('span');
            text.className = 'todo-text';
            text.textContent = todo.text;
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'delete-btn';
            remove.dataset.id = todo.id;
            remove.setAttribute('aria-label', `Hapus ${todo.text}`);
            remove.innerHTML = '<i class="fas fa-trash-alt" aria-hidden="true"></i>';
            content.append(checkbox, text);
            li.append(content, remove);
            todoList.appendChild(li);
        });
    }

    // Update jumlah tugas yang tersisa
    const pendingCount = todos.filter(todo => !todo.completed).length;
    itemsLeft.innerText = `${pendingCount} tugas tersisa`;
}

// Event: Menambah Tugas Baru
todoForm.addEventListener('submit', (e) => {
    e.preventDefault(); // Mencegah reload halaman

    const text = todoInput.value.trim();
    if (text !== '') {
        const newTodo = {
            id: crypto.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`,
            text: text,
            completed: false
        };

        if (todos.length >= 200) todos.shift();
        todos.push(newTodo);
        saveToLocalStorage();
        todoInput.value = '';
        renderTodos();
    }
});

// Event: Menandai Selesai atau Menghapus Tugas (Event Delegation)
todoList.addEventListener('click', (e) => {
    // Jika tombol hapus diklik
    if (e.target.closest('.delete-btn')) {
        const id = e.target.closest('.delete-btn').dataset.id;
        todos = todos.filter(todo => todo.id !== id);
        saveToLocalStorage();
        renderTodos();
    }

    // Jika checkbox diklik
    if (e.target.classList.contains('todo-checkbox')) {
        const id = e.target.dataset.id;
        const todo = todos.find(t => t.id === id);
        if (todo) {
            todo.completed = e.target.checked;
            saveToLocalStorage();
            renderTodos();
        }
    }
});

// Event: Menghapus Semua yang Selesai
clearCompletedBtn.addEventListener('click', () => {
    todos = todos.filter(todo => !todo.completed);
    saveToLocalStorage();
    renderTodos();
});

// Event: Mengubah Filter
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Hapus kelas active dari semua tombol
        filterBtns.forEach(b => b.classList.remove('active'));
        // Tambahkan ke tombol yang diklik
        btn.classList.add('active');

        // Ubah state filter dan render ulang
        currentFilter = btn.dataset.filter;
        renderTodos();
    });
});

// Render pertama kali saat halaman dimuat
document.addEventListener('DOMContentLoaded', renderTodos);
