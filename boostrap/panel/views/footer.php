</div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script personalizado -->
    <script>
        // Validar contraseña coincida en formulario crear
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            if(form) {
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('password_confirm');
                
                if(passwordInput && confirmInput) {
                    form.addEventListener('submit', function(e) {
                        if(passwordInput.value !== confirmInput.value) {
                            e.preventDefault();
                            alert('Las contraseñas no coinciden');
                            confirmInput.focus();
                        }
                    });
                }
            }
        });
    </script>
    
</body>
</html>