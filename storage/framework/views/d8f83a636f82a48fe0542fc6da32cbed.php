<?php $__env->startSection('template_title', $metodosAhorro->name ?? __('Mostrar') . ' ' . __('Método de Ahorro')); ?>

<?php $__env->startSection('content'); ?>
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white">
                <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-uppercase font-weight-bold">
                        <?php echo e(__('Detalles del Método de Ahorro')); ?>

                    </h5>
                    <a class="btn btn-danger btn-sm" href="<?php echo e(route('metodos-ahorros.index')); ?>" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Volver')); ?>

                    </a>
                </div>

                <div class="card-body bg-dark text-white">
                    <div class="form-group mb-3">
                        <strong class="text-warning"><?php echo e(__('Nombre del Método:')); ?></strong>
                        <p class="mb-0"><?php echo e($metodosAhorro->nom_metodo); ?></p>
                    </div>

                    <div class="form-group mb-3">
                        <strong class="text-warning"><?php echo e(__('Descripción:')); ?></strong>
                        <p class="mb-0"><?php echo e($metodosAhorro->descripcion); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/metodos-ahorro/show.blade.php ENDPATH**/ ?>