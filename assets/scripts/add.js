const form = document.getElementById('addOrganisation');

form.addEventListener("submit", function(e) {
    e.preventDefault();
    const organization = document.getElementById('organization').value;
    const password = document.getElementById('password').value;

    fetch('/handler/addOrganization.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ organization, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                alert(data.message);
                form.reset();
                setTimeout(() => {
                    window.location.href = '/pages/admin.php';
                }, 3000);
            } else {
                document.getElementById('responseMessage').innerText = data.errors[0].message;
            }
        })
        .catch(error => console.error('Ошибка:', error));
})