<?php $__env->startSection('template_title', 'Porcentajes Ahorros'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 bg-dark text-white" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0 text-uppercase font-weight-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        <?php echo e(__('Ahorros & Porcentajes')); ?>

                    </h5>
                    <a href="<?php echo e(route('porcentajes-ahorros.create')); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-plus-circle"></i> <?php echo e(__('Nuevo')); ?>

                    </a>
                </div>

                <?php if($message = Session::get('success')): ?>
                    <div class="alert alert-success m-3 text-dark" style="border-radius: 8px;"><?php echo e($message); ?></div>
                <?php endif; ?>

                <div class="card-body bg-dark text-white p-4" style="border-radius: 0 0 12px 12px;">
                    <div class="table-responsive">
                        <table class="table table-hover table-dark text-center align-middle" style="table-layout: fixed; width: 100%; border-radius: 12px;">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Método</th>
                                    <th style="width: 13%;">% 1</th>
                                    <th style="width: 13%;">% 2</th>
                                    <th style="width: 13%;">% 3</th>
                                    <th style="width: 13%;">% 4</th>
                                    <th style="width: 23%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $porcentajesAhorros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $porcentajesAhorro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr style="border-radius: 8px;">
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($porcentajesAhorro->metodo?->nom_metodo); ?></td>
                                        <td><?php echo e($porcentajesAhorro->porcentaje_1); ?>%</td>
                                        <td><?php echo e($porcentajesAhorro->porcentaje_2); ?>%</td>
                                        <td><?php echo e($porcentajesAhorro->porcentaje_3); ?>%</td>
                                        <td><?php echo e($porcentajesAhorro->porcentaje_4); ?>%</td>
                                        <td>
                                            <a href="<?php echo e(route('porcentajes-ahorros.show', $porcentajesAhorro->id)); ?>" class="btn btn-sm btn-info mx-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('porcentajes-ahorros.edit', $porcentajesAhorro->id)); ?>" class="btn btn-sm btn-success mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('porcentajes-ahorros.destroy', $porcentajesAhorro->id)); ?>" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este registro?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger mx-1">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <?php echo $porcentajesAhorros->withQueryString()->links(); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/porcentajes-ahorro/index.blade.php ENDPATH**/ ?>