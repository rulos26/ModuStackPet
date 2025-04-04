<?php $__env->startSection('template_title'); ?>
    <?php echo e($porcentajesAhorro->name ?? __('Mostrar') . " " . __('Porcentajes de Ahorro')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold">
                        <?php echo e(__('Detalle del Porcentaje de Ahorro')); ?>

                    </h5>
                    <a href="<?php echo e(route('porcentajes-ahorros.index')); ?>" class="btn btn-danger btn-sm" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Volver')); ?>

                    </a>
                </div>

                <div class="card-body bg-dark text-white p-4" style="border-radius: 0 0 12px 12px;">
                    <div class="mb-3">
                        <strong>📌 Método :</strong>
                        <span class="text-warning"><?php echo e($porcentajesAhorro->metodo?->nom_metodo); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 1:</strong>
                        <span class="text-success"><?php echo e($porcentajesAhorro->porcentaje_1); ?>%</span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 2:</strong>
                        <span class="text-success"><?php echo e($porcentajesAhorro->porcentaje_2); ?>%</span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 3:</strong>
                        <span class="text-success"><?php echo e($porcentajesAhorro->porcentaje_3); ?>%</span>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Porcentaje 4:</strong>
                        <span class="text-success"><?php echo e($porcentajesAhorro->porcentaje_4); ?>%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/porcentajes-ahorro/show.blade.php ENDPATH**/ ?>