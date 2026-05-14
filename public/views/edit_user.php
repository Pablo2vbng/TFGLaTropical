<?php

session_start();

// CHULETA -> TABLA: users (id, name, email, phone, instrument, role, is_approved)
// CHULETA -> TABLA: audit_logs (id, action, target, user_id)

// SEGURIDAD: VERIFICAMOS QUE EL USUARIO TENGA PERMISOS DE ADMINISTRADOR
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}

require_once '../../config/database.php';

// VALIDACION: COMPROBAMOS QUE LLEGUE UN ID VALIDO POR URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = (int)$_GET['id'];

// CONSULTA: OBTENEMOS LOS DATOS DEL MUSICO PARA RELLENAR EL FORMULARIO
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("Usuari no trobat");
}
?>

<?php require_once '../../includes/header_views.php'; ?>
<?php require_once '../../includes/navbar_views.php'; ?>

<!-- BOOTSTRAP DOCU: CONTENEDORES https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="container py-5" style= "min-height: 100vh; padding-top: 5rem;">



    <!-- BOOTSTRAP DOCU: CARDS https://getbootstrap.com/docs/5.3/components/card/ -->
    <div class="card shadow-sm border-0">
        
        <div class="card-header bg-dark text-white fw-bold">
            <?php // DIV CABECERA: TITULO DE LA FICHA DE EDICION CON FONDO OSCURO ?>
            Editar Perfil de Músic: <?php echo htmlspecialchars($user['name']); ?>
        </div>

        <div class="card-body p-4">
            <?php // DIV CUERPO: CONTENEDOR DEL FORMULARIO CON PADDING EXTRA (P-4) ?>
            
            <!-- BOOTSTRAP DOCU: FORMULARIOS https://getbootstrap.com/docs/5.3/forms/overview/ -->
            <form method="POST" action="../../controllers/UserController.php">
                <?php // ELEGIMOS CON CAMPOS IMPUT HTIDEN LA ACCION UPDATE ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                
                <!-- BOOTSTRAP DOCU: GRID SYSTEM https://getbootstrap.com/docs/5.3/layout/grid/ -->
                <div class="row g-3">
                    <?php // BOOTSTRAP: SISTEMA DE REJILLA CON ESPACIADO ENTRE COLUMNAS (GUTTERS) ?>
                    
                    <div class="col-md-12">
                        <!-- BOOTSTRAP DOCU: FORM CONTROLS https://getbootstrap.com/docs/5.3/forms/form-control/ -->
                        <label class="form-label fw-bold">Nom Complet</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Telèfon</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Instrument</label>
                        <input type="text" name="instrument" class="form-control" value="<?php echo htmlspecialchars($user['instrument']); ?>" placeholder="Ex: Clarinet, Percussió...">
                    </div>

                    <div class="col-md-6">
                        <!-- BOOTSTRAP DOCU: SELECTS https://getbootstrap.com/docs/5.3/forms/select/ -->
                        <label class="form-label fw-bold">Rol en el sistema</label>
                        <select name="role" class="form-select">
                            <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>Músic (Estàndard)</option>
                            <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                        <?php // BOOTSTRAP DOCU: SELECTS  https://getbootstrap.com/docs/5.3/forms/select/ ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Estat del compte</label>
                        <select name="is_approved" class="form-select">
                            <option value="1" <?php echo ($user['is_approved'] == 1) ? 'selected' : ''; ?>>Aprovat / Actiu</option>
                            <option value="0" <?php echo ($user['is_approved'] == 0) ? 'selected' : ''; ?>>Pendent d'aprovació</option>
                        </select>
                    </div>
                </div>

                <!-- BOOTSTRAP DOCU: UTILIDADES DE FLEXBOX https://getbootstrap.com/docs/5.3/utilities/flex/ -->
                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <?php // DIV ACCIONES: SEPARACION VISUAL CON LINEA SUPERIOR (BORDER-TOP) ?>
                    
                    <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary px-4">Tornar al tauler</a>
                    <button type="submit" class="btn btn-primary px-5">Guardar Canvis del Perfil</button>
                    
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>