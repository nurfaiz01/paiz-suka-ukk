<?php
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $type = isset($_SESSION['flash_type']) ? $_SESSION['flash_type'] : 'info';
    ?>
    <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}
?>