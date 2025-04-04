<?php $__env->startSection('template_title', 'Nuevo Porcentaje de Ahorro'); ?>

<?php $__env->startSection('content'); ?>
<section class="content container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white text-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <?php echo e(__('Agregar Porcentaje de Ahorro')); ?>

                    </h5>
                </div>
                <div class="card-body bg-dark text-white p-4" style="border-radius: 0 0 12px 12px;">
                    <form method="POST" action="<?php echo e(route('porcentajes-ahorros.store')); ?>" role="form" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('porcentajes-ahorro.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="<?php echo e(route('porcentajes-ahorros.index')); ?>" class="btn btn-light me-2" style="border-radius: 8px;">
                                <i class="fas fa-arrow-left"></i> <?php echo e(__('Cancelar')); ?>

                            </a>
                            <button type="submit" class="btn btn-success" style="border-radius: 8px;">
                                <i class="fas fa-save"></i> <?php echo e(__('Guardar')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/porcentajes-ahorro/create.blade.php ENDPATH**/ ?>