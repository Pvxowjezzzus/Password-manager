document.addEventListener("DOMContentLoaded", (e) => {


    const form = document.getElementById('addServiceForm');
    if (form) {
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const service = document.getElementById('service').value;
            const password = document.getElementById('password').value;
            fetch('/handler/createService.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ service, password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        alert(data.message);
                        form.reset();
                        window.location.href = '/pages/admin.php';
                    } else {
                        for (let error of data.errors) {
                            alert(error['message']);
                        }
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        });

    }
});