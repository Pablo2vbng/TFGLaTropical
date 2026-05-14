document.addEventListener("DOMContentLoaded", function() {
    const forms = document.querySelectorAll("form");

    forms.forEach(form => {
        form.addEventListener("submit", function(event) {
            let esValido = true;
            let mensajeError = "";

            // VALIDACIÓN DE TEXTO GENERAL (Nombre, Título, Instrumento) ---
            // No permite campos vacíos o solo espacios
            const camposTexto = form.querySelectorAll('input[type="text"], textarea');
            camposTexto.forEach(input => {
                if (input.hasAttribute('required') && input.value.trim().length < 3) {
                    mensajeError = "El camp '" + input.previousElementSibling.innerText + "' ha de tindre almenys 3 caràcters.";
                    marcarError(input);
                    esValido = false;
                }
            });

            // 2. VALIDACIÓN DE EMAIL (Regex Estándar)
            const emailInput = form.querySelector('input[type="email"]');
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (emailInput && !emailRegex.test(emailInput.value)) {
                mensajeError = "L'adreça de correu electrònic no és vàlida.";
                marcarError(emailInput);
                esValido = false;
            }

            // VALIDACIÓN DE TELÉFONO (9 dígitos, empieza por 6, 7 o 9) 
            const phoneInput = form.querySelector('input[name="phone"]');
            const phoneRegex = /^[679]\d{8}$/;
            if (phoneInput && phoneInput.value !== "" && !phoneRegex.test(phoneInput.value)) {
                mensajeError = "El telèfon ha de tindre 9 dígits i començar per 6, 7 o 9.";
                marcarError(phoneInput);
                esValido = false;
            }

            // VALIDACIÓN DE PRECIOS (base_price, price_modifier)
            const priceInputs = form.querySelectorAll('input[name="base_price"], input[name*="price_modifier"]');
            priceInputs.forEach(input => {
                if (input.value !== "" && parseFloat(input.value) < 0) {
                    mensajeError = "Els imports econòmics no poden ser negatius.";
                    marcarError(input);
                    esValido = false;
                }
            });

            // VALIDACIÓN DE FECHAS (No crear eventos en el pasado) 
            const dateInput = form.querySelector('input[name="date"]');
            if (dateInput && dateInput.value !== "") {
                const fechaSeleccionada = new Date(dateInput.value);
                const hoy = new Date();
                if (fechaSeleccionada < hoy) {
                    mensajeError = "La data de l'acte no pot ser anterior a hui.";
                    marcarError(dateInput);
                    esValido = false;
                }
            }

            // 6. VALIDACIÓN DE CONTRASEÑA (Mínimo 6 caracteres, una letra y un número) 
            const passInput = form.querySelector('input[name="password"]');
            const passRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
            if (passInput && passInput.value !== "" && !passRegex.test(passInput.value)) {
                mensajeError = "La contrasenya ha de tindre almenys 6 caràcters, incloent una lletra i un número.";
                marcarError(passInput);
                esValido = false;
            }

            // SI HAY ERRORES, BLOQUEAMOS Y AVISAMOS
            if (!esValido) {
                event.preventDefault();
                alert(mensajeError);
            }
        });
    });

    // FUNCION PARA RESALTAR LE CAMPO ERROR
    function marcarError(element) {
        element.classList.add("is-invalid");
        element.focus();
        // LIMPIAMOS LA CLASE
        element.addEventListener("input", function() {
            element.classList.remove("is-invalid");
        });
    }
});