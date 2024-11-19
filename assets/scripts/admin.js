document.addEventListener("DOMContentLoaded", () => {
    let id = null;
    let access_list = document.querySelector('.access_list');
    const show_modal = document.getElementById("show__modal");
    document.querySelectorAll(".show_pswd").forEach(show => {
        show.addEventListener("click", () => {
            id = show.parentElement.parentElement.querySelector('.org_id').innerHTML;
            show_modal.classList.add('show-modal');
        })
    })
    show_modal.querySelector('span').addEventListener("click", function() {
        show_modal.classList.remove('show-modal');
    })
    const pswd = document.getElementById('showPassword');
    pswd.addEventListener('submit', function(e) {
        e.preventDefault();
        const submit = document.getElementById('submit__pswd');
        submit.disabled = true;
        const pswd_hash = document.getElementById('password_hash').value;
        fetch('/handler/showPassword.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id, pswd_hash })
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

    })
    document.querySelectorAll("#edit_org").forEach(edit => {
        edit.addEventListener("click", function() {
            document.querySelectorAll("#edit_org").forEach(btn => {
                btn.disabled = true;
            });

            document.getElementById('password-input').innerHTML = '';
            document.getElementById('organization_name').value = '';
            access_list.innerHTML = '';
            id = edit.parentElement.parentElement.querySelector('.org_id').innerHTML;
            fetch('/handler/editOrganization.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                }).then(response => response.json())
                .then(data => {

                    document.getElementById('organization_name').value = data.organizationname;
                    const AccessNew = document.createElement("form");
                    AccessNew.classList.add('newAccessForm');


                    let newAccessOption = data.usersID.map(function(id) {
                        input = `<option value="${id['UserID']}">Пользователь ${id['login']}</option>`;
                        return input;
                    });

                    AccessNew.innerHTML = `
                                        <p>Добавить нового владельца</p>
                                        <select name="newaccess" form="newAccessForm" id="newAccessForm">
                                        <option value="" selected>-</option>
                                        ${newAccessOption}
                                       </select>`;
                    access_list.appendChild(AccessNew);
                    let pswd = document.createElement('input');
                    pswd.setAttribute("type", "password");
                    pswd.setAttribute("name", "new-password");
                    pswd.setAttribute("id", "new-password");
                    document.getElementById('password-input').appendChild(pswd);

                    document.querySelectorAll("#edit_org").forEach(btn => {
                        btn.disabled = false;
                    });
                })
                .catch(error => console.error('Ошибка выхода:', error));
            document.getElementById('editOrganization').classList.add('show__edit-form');
        })
    });
    document.getElementById('editOrganization').addEventListener('submit', function(e) {
        e.preventDefault();
        if (id !== null) { // Проверка на то, определена ли переменная id (id организации, которая редактируется)
            const org_name = document.querySelector('#editOrganization #organization_name').value;
            const new_access = document.querySelector('#editOrganization #newAccessForm').value;
            const new_password = document.querySelector('#editOrganization #new-password').value;
            fetch('/handler/editOrganization.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, org_name, new_access, new_password })
                }).then(response => response.json())
                .then(data => {
                    if (!data.valid) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                        window.location.reload();
                    }
                })
        }

    });
    document.querySelectorAll("#del_org").forEach(del => {
        del.addEventListener("click", function() {
            del.disabled = true;
            id = del.parentElement.parentElement.querySelector('.org_id').innerHTML;
            fetch('/handler/deleteOrganization.php', {
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
        })
    });
    document.getElementById('logout-btn').addEventListener("click", function() {
        const logout = fetch('/handler/logout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).catch(error => console.error('Ошибка выхода:', error));
        if (logout) {
            window.location.href = '/';
        } else {
            alert("Ошибка выхода");
        }

    })
})