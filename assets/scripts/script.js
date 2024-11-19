const form = document.getElementById('enter-form');

const fields = [
    'login',
    'password'
];

form.addEventListener('submit', function(e) {
    e.preventDefault();
    delGotoAdmin();
    const login = document.getElementById('login').value;
    const password = document.getElementById('password').value;
    fetch('/handler/validate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ login, password })
        })
        .then(response => response.json())
        .then(data => {
            const errors = document.querySelectorAll('.responseMessage')
            errors.forEach(function(elem) {
                elem.previousElementSibling.style.border = '1px black solid';
                elem.innerHTML = '';
                elem.classList.remove('valid__text', 'invalid__text')
            });
            if (!data.valid) {
                for (let error of data.errors) {
                    if (data.invalid.includes('all')) {
                        let responseMessage = document.querySelector(`#response_all`);
                        responseMessage.classList.add('invalid__text');
                        responseMessage.innerText = error['message'];
                        fields.forEach(function(elem) {
                            let input = document.querySelector(`#${elem}`);
                            input.style.border = '1px red solid';
                        })
                    }
                    if (error.field !== 'all') {
                        let input = document.querySelector(`#${error['field']}`);
                        let responseMessage = document.querySelector(`#response_${error['field']}`);
                        responseMessage.classList.add('invalid__text');
                        responseMessage.innerText = error['message'];
                        input.style.border = '1px red solid';

                    }


                }
                if (data.done !== '')
                    data.done.forEach(field => fillOk(field));
            } else {
                fields.forEach(field => fillOk(field));
                let responseMessage = document.querySelector(`#response_all`);
                responseMessage.innerText = data.message;
                responseMessage.classList.add('valid__text');
                authorizeUser(login, password);
            }


        })

    .catch(error => console.error('Ошибка:', error));
})

function authorizeUser(login, password) {
    delGotoAdmin();
    fetch('/handler/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ login, password })
        })
        .then(response => response.json())
        .then(data => {
            let choose = document.querySelector('.choose');
            if (data.ok) {
                if (data.role == 'admin') {
                    alert('Вы админ');
                    choose.innerHTML += data.gotoPA;
                } else if (data.role == 'user') {
                    alert('Вы пользователь');
                }
                choose.innerHTML += data.checkHASH;
            }
        })
        .catch(error => console.error('Ошибка:', error));
}

function showModal() {
    const show_modal = document.getElementById("show__modal");
    show_modal.classList.add('show-modal');
};

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
        .catch(error => console.error('Ошибка:', error));
}

function gotoAdmin() { // Переход в панель Админа по нажатию на кнопку
    let isAdmin = true;
    // Отправялеям полученный JSON на сервер обычным POST-запросом:
    fetch('/handler/auth.php', {
            'method': 'POST',
            'headers': {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ isAdmin })
        }).then(response => response.json()).then(data => {
            if (data.gotoPA == true) {
                window.location.href = '/pages/admin.php';
            }
        })
        .catch(error => console.error('Ошибка:', error));
}

function delGotoAdmin() {
    let btn = document.querySelector('.goto-admin');
    if (btn) {
        btn.remove();
    }
    let hash = document.querySelector('#show_pswd');
    if (hash) {
        hash.remove();
    }

}

function fillOk(currentField) {

    let successField = document.querySelector(`#response_${currentField}`);
    if (successField) {
        successField.innerHTML = '✔️';
        successField.classList.add('valid__text');
        successField.previousElementSibling.style.border = '1px green solid';
    }

}