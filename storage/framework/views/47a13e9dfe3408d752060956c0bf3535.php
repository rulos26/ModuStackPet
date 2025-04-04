<?php $__env->startSection('template_title', __('Métodos de Ahorro')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <?php echo e(__('Métodos de Ahorro')); ?>

                    </h5>
                    <a href="<?php echo e(route('metodos-ahorros.create')); ?>" class="btn btn-danger btn-sm" style="border-radius: 8px;">
                        <i class="fas fa-plus"></i> <?php echo e(__('Crear Nuevo')); ?>

                    </a>
                </div>

                <?php if($message = Session::get('success')): ?>
                    <div class="alert alert-success m-3 text-dark" style="border-radius: 8px;">
                        <p class="mb-0"><?php echo e($message); ?></p>
                    </div>
                <?php endif; ?>

                <div class="card-body bg-dark text-white p-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-dark text-center align-middle" style="table-layout: fixed; width: 100%; border-radius: 12px;">
                            <thead class="bg-secondary text-white">
                                <tr>
                                   
                                    <th style="width: 1%;">#</th>
                                    <th style="width: 20%;"><?php echo e(__('Nombre del Método')); ?></th>
                                    <th style="width: 44%;"><?php echo e(__('Descripción')); ?></th>
                                    <th style="width: 30%;"><?php echo e(__('Acciones')); ?></th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $metodosAhorros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $metodosAhorro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td class="text-warning"><?php echo e($metodosAhorro->nom_metodo); ?></td>
                                        <td><?php echo e($metodosAhorro->descripcion); ?></td>
                                        <td>
                                            <form action="<?php echo e(route('metodos-ahorros.destroy', $metodosAhorro->id)); ?>" method="POST">
                                                <a class="btn btn-sm btn-info mx-1" href="<?php echo e(route('metodos-ahorros.show', $metodosAhorro->id)); ?>">
                                                    <i class="fas fa-eye"></i> 
                                                </a>
                                                <a class="btn btn-sm btn-success mx-1" href="<?php echo e(route('metodos-ahorros.edit', $metodosAhorro->id)); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('¿Estás seguro de eliminar este método de ahorro?')">
                                                    <i class="fas fa-trash"></i> 
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-secondary text-white d-flex justify-content-center mt-3">
                    <?php echo $metodosAhorros->withQueryString()->links(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/metodos-ahorro/index.blade.php ENDPATH**/ ?>