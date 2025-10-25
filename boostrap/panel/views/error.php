<?php if (isset($alerta['mensaje']) && isset($alerta['tipo'])): ?>
<div class="alert alert-<?php echo ($alerta['tipo']); ?> alert-dismissible fade show" role="alert">
  <strong>
    <?php if($alerta['tipo'] == 'success'): ?>
      <i class="fas fa-check-circle"></i>
    <?php elseif($alerta['tipo'] == 'danger'): ?>
      <i class="fas fa-exclamation-circle"></i>
    <?php elseif($alerta['tipo'] == 'warning'): ?>
      <i class="fas fa-exclamation-triangle"></i>
    <?php else: ?>
      <i class="fas fa-info-circle"></i>
    <?php endif; ?>
  </strong>
  <?php echo ($alerta['mensaje']); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>