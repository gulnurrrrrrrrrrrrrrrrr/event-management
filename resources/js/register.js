$(document).ready(function() {

    $("#registerForm").submit(function(event) {
        event.preventDefault();

        const message = $("#registerMessage");

        const formData = {
            name: $("#name").val().trim(),
            email: $("#email").val().trim(),
            password: $("#password").val(),
            confirmPassword: $("#confirmPassword").val(),
            birthdate: $("#birthdate").val(),
            age: $("#age").val(),
            gender: $("input[name='gender']:checked").val() || null,
            city: $("#city").val(),
        };

        if (formData.password !== formData.confirmPassword) {
            showMessage("Пароли не совпадают!", "error");
            return;
        }

        if (formData.password.length < 6) {
            showMessage("Пароль должен быть минимум 6 символов.", "error");
            return;
        }

        if (!formData.gender) {
            showMessage("Выберите пол", "error");
            return;
        }

        if (!formData.city) {
            showMessage("Выберите город", "error");
            return;
        }

        showMessage("Регистрация прошла успешно! ", "success");

        console.log("Данные формы:", formData);

        $("#registerForm")[0].reset();
    });

    function showMessage(text, type) {
        const message = $("#registerMessage");
        message.text(text);
        message.removeClass("success error").addClass(type);
    }
});