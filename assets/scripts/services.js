    document.querySelectorAll("#del_service").forEach(del => {
        del.addEventListener("click", function() {
            del.disabled = true;
            id = del.parentElement.parentElement.querySelector('#service_id').innerHTML;
            fetch('/handler/deleteService.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                }).then(response => response.json())
                .then(data => {
                    if (!data.ok & data.error) {
                        alert(data.message)
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                })

        });
    })
    document.getElementById("show_pswd") ?
        document.getElementById("show_pswd").addEventListener("click", function() {
            const show_modal = document.getElementById("show__modal");
            show_modal.classList.add('show-modal');
        }) : null;

    function hideModal() {
        const show_modal = document.getElementById("show__modal");
        show_modal.classList.remove('show-modal');
    }

    function showPassword(e) {
        e.preventDefault();
        const submit = document.getElementById('submit__pswd');
        submit.disabled = true;
        const pswd_hash = document.getElementById('password_hash').value;
        fetch('/handler/showPswd.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ pswd_hash })
            })
            .then(response => response.json())
            .then(data => {

                if (data.valid) {
                    document.querySelector('.password').innerHTML = data.message;
                    setTimeout(() => {
                        window.location.reload();
                    }, 4000);
                } else if (data.error == true) {
                    alert(data.message);
                    submit.disabled = false;
                }
            })
            .catch(error => console.error('Ошибка:', error))
    }