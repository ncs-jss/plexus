// Admin Login
$(document).ready(function() {
    var login = {};
    $("#login").click(function() {
        login.username = $("#username").val();
        login.password = $("#password").val();
        $.post(baseUrl+"society/login", login,function(data, status) {
                console.log("Data: " + data + "\nStatus: " + status);
                console.log(JSON.stringify(data));
                window.location = data.redirect;
        });
    });
});