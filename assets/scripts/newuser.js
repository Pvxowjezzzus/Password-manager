const addUserForm = document.getElementById('addUser');

addUserForm.addEventListener("submit", function(e) {
    e.preventDefault();
    const login = document.getElementById('login').value;
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;
    fetch('/handler/addUser.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ login, password, role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                alert(data.message);
                addUserForm.reset();
                setTimeout(() => {
                    window.location.href = '/pages/admin.php';
                }, 1000);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Ошибка:', error));
})