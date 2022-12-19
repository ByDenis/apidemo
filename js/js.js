const limit_show_user = 5;

let errorMsg = (msg) => {
    document.getElementById('error-msg').innerHTML = msg;
};

let login = () => {
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
       
        document.getElementById('login-button').value='Wait...';
        document.getElementById('error-msg').innerHTML = '&nbsp;';

        const formData = new FormData();
        formData.append('user', document.querySelector('input[name="name"]').value);
        formData.append('pass', document.querySelector('input[name="pass"]').value);
        formData.append('remember', document.querySelector('input[name="is_remember"]').checked);
        
        fetch("/api/auth", {
            method: "POST",
            body: formData,
        }).then(function (response) {
            if (response.status === 200) {
                location.href = '/list.html';
            } else {
                response.json().then((data) => {
                    errorMsg(data.msg);
                    document.getElementById('login-button').value='Log In';
                });
            }
        }).catch(function (error) {
            console.error(error);
        });
    });
}

let logut = () => {
    fetch("/api/auth", {
        method: "DELETE",
    }).then(function (response) {
        if (response.status === 200) {
            location.href = 'index.html';
        } 
    }).catch(function (error) {
        console.error(error);
    });
};

let get_users_data = (offset, limit) => {
    document.getElementById('user-list').innerHTML = '';
    document.getElementById('user-pages').innerHTML = '';

    fetch("/api/users?offset="+offset+"&limit="+limit, {
        method: "GET",
    })
    .then((response) => {
        if (response.status === 200) return response.json();
        document.cookie = '';
        location.href = '/index.html';
    })
    .then(function (data) {
        let listData = '';
        data.list.map((item) => {
            listData +=`
            <div class="user-entry">
                <img src="/img/verified.svg" alt="verified" class="verified"/>
                <span class="user-id">${item.nicname}</span>
                <span class="user-name">${item.name} ${item.surname}</span>
                <span class="group-name-pre">...</span>
                <span class="group-name">${item.groupname}</span>
            </div>	
            `;
        })
        document.getElementById('user-list').innerHTML = listData;
       
        let listPages = '';
        if (data.page>1) {
            let prev = data.page-1;
            listPages += `<a href="#${prev}">&laquo; Previous</a>`;
        }
        for(let i=1; i<=data.pages; i++) {
            if (data.page === i) {
                listPages += `<span>${i}</span>`;
            } else {
                listPages += `<a href="#${i}">${i}</a>`;
            }
        }
        if (data.page<data.pages) {
            let next = data.page+1;
            listPages += `<a href="#${next}">Next &raquo;</a>`;
        }
        document.getElementById('user-pages').innerHTML = listPages;
        
    }).catch(function (error) {
        console.error(error);
    });
};

const url = new URL(location.href);

if (url.pathname === '/index.html' || url.pathname === '/') {
    if (document.cookie.indexOf("token") === -1 && document.cookie.indexOf("user") === -1) {
        document.addEventListener("DOMContentLoaded",login);
    } else {
        location.href = '/list.html';
    }
} else if (url.pathname === '/list.html') {
    document.addEventListener("DOMContentLoaded",()=> {
        if (location.hash != '' && location.hash!= '#') {
            let page = parseInt(location.hash.replace(/#/g,'')) - 1;
            get_users_data(page*limit_show_user,limit_show_user);
        } else {
            get_users_data(0,limit_show_user);
        }
    
        document.getElementById('user-pages').addEventListener('click', function(e) {
            if(e.target.tagName.toLowerCase() === 'a') {
                let page = parseInt(e.target.hash.replace(/#/g,'')) - 1;
                get_users_data(page*limit_show_user,limit_show_user);
            }
        });
    
        document.getElementById('user-logout').addEventListener('click', function(e) {
            e.preventDefault();
            logut();
        });
    });
} else {
    location.href = '/index.html';
}

