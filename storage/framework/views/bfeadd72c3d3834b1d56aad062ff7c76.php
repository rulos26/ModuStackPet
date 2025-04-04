<?php $__env->startSection('template_title', __('Actualizar Método de Ahorro')); ?>

<?php $__env->startSection('content'); ?>
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white">
                <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-uppercase font-weight-bold">
                        <i class="fas fa-edit"></i> <?php echo e(__('Editar Método de Ahorro')); ?>

                    </h5>
                    <a class="btn btn-danger btn-sm" href="<?php echo e(route('metodos-ahorros.index')); ?>" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Volver')); ?>

                    </a>
                </div>

                <div class="card-body bg-dark">
                    <form method="POST" action="<?php echo e(route('metodos-ahorros.update', $metodosAhorro->id)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <?php echo $__env->make('metodos-ahorro.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-warning px-4" style="border-radius: 8px;">
                                <i class="fas fa-sync-alt"></i> <?php echo e(__('Actualizar Método')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/metodos-ahorro/edit.blade.php ENDPATH**/ ?>