<?php
session_start();

// CHULETA -> TABLA: audit_logs (id, action, target, user_id, created_at)
// CHULETA -> TABLA: users (id, name)

// SEGURIDAD: SOLO EL ADMINISTRADOR PUEDE CONSULTAR EL HISTORIAL COMPLETO
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: ../login.php"); 
    exit(); 
}

require_once '../../config/database.php';

// CONSULTA PARA OBTENER LOS REGISTROS DEL USUARIO ADMIN
$logs = $conn->query("SELECT al.*, u.name as admin_name 
                    FROM audit_logs al 
                    JOIN users u 
                    ON al.user_id = u.id 
                    ORDER BY al.created_at 
                    DESC")->fetchAll();
?>

<?php require_once '../../includes/header_views.php'; ?>
<?php require_once '../../includes/navbar_views.php'; ?>

<!-- BOOTSTRAP DOCU: CONTENEDORES https://getbootstrap.com/docs/5.3/layout/containers/ -->
<main class="container py-5" style="margin-top: 80px;">

    <!-- BOOTSTRAP DOCU: UTILIDADES FLEX Y ALINEACIÓN https://getbootstrap.com/docs/5.3/utilities/flex/ -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">Historial d'activitat</h1>
        <!-- BOOTSTRAP DOCU: BOTONES https://getbootstrap.com/docs/5.3/components/buttons/ -->
        <a href="admin_dashboard.php" class="btn btn-secondary">Tornar</a>
    </div>

    <!-- BOOTSTRAP DOCU: CARDS https://getbootstrap.com/docs/5.3/components/card/ -->
    <div class="card shadow-sm border-0">
        <!-- SCSS: .shadow-sm APLICA LA SOMBRA Y .border-0 ELIMINA EL CONTORNO PARA UN LOOK MAS LIMPIO -->
        
        <!-- BOOTSTRAP DOCU: TABLAS RESPONSIVE https://getbootstrap.com/docs/5.3/content/tables/#responsive-tables -->
        <div class="table-responsive">
            <!-- BOOTSTRAP DOCU: ESTILOS DE TABLAS https://getbootstrap.com/docs/5.3/content/tables/ -->
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Data</th>
                        <th>Admin</th>
                        <th>Acció</th>
                        <th>Mòdul</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($logs as $l): ?>
                    <tr>
                        <td>
                            <!-- DETALLE PARA OBTENER FECHA Y HORA -->
                            <small><?php echo date('d/m/y H:i:s', strtotime($l['created_at'])); ?></small>
                        </td>
                        <td class="fw-bold">
                            <!-- DETALLE PARA OBTENER EL NOMBRE DE QUIEN LO ADMINISTRA -->
                            <?php echo htmlspecialchars($l['admin_name']); ?>
                        </td>
                        <td>
                            <!-- DETALLE DE LA ACCION REALIZADA (APROBACIONES, EDICIONES, PAGOS) -->
                            <?php echo htmlspecialchars($l['action']); ?>
                        </td>
                        <td>
                            <!-- BOOTSTRAP DOCU: BADGES https://getbootstrap.com/docs/5.3/components/badge/ -->
                            <span class="badge bg-light text-dark border">
                                <?php echo strtoupper($l['target']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (count($logs) === 0): ?>
            <!-- BOOTSTRAP DOCU: UTILIDADES DE TEXTO Y ESPACIADO https://getbootstrap.com/docs/5.3/utilities/spacing/ -->
            <div class="p-5 text-center text-muted">
                No hi ha registres d'activitat per mostrar.
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>