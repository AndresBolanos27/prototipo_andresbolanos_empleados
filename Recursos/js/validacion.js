$(document).ready(function() {
    $("#empleadoForm").validate({
        rules: {
            nombre: {
                required: true,
                minlength: 5
            },
            correo: {
                required: true,
                email: true
            },
            sexo: {
                required: true
            },
            area: {
                required: true
            },
            descripcion: {
                required: true,
                minlength: 10
            },
            "roles[]": {
                required: true,
                minlength: 1
            }
        },
        messages: {
            nombre: {
                required: "Por favor, ingresa el nombre completo",
                minlength: "El nombre debe tener al menos 5 caracteres"
            },
            correo: {
                required: "Por favor, ingresa un correo electrónico",
                email: "Por favor, ingresa un correo electrónico válido"
            },
            sexo: {
                required: "Por favor, selecciona tu sexo"
            },
            area: {
                required: "Por favor, selecciona un área"
            },
            descripcion: {
                required: "Por favor, proporciona una descripción",
                minlength: "La descripción debe tener al menos 10 caracteres"
            },
            "roles[]": {
                required: "Por favor, selecciona al menos un rol",
                minlength: "Selecciona al menos un rol"
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {
                error.appendTo(element.closest(".col-sm-10"));
            } else {
                error.insertAfter(element);
            }
        },
        errorElement: "div",
        errorClass: "text-danger mt-2"
    });
});
