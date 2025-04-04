<div class="row padding-1 p-1">
    <div class="col-md-12">


        <div class="form-group mb-2 mb-20">
            <label for="metodo_id" class="form-label"><?php echo e(__('Método')); ?></label>
            <select name="metodo_id" class="form-control <?php $__errorArgs = ['metodo_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="metodo_id">
                <option value="">Seleccione un método</option>
                <?php $__currentLoopData = $metodos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($metodo->id); ?>" <?php echo e(old('metodo_id', $porcentajesAhorro?->metodo_id) == $metodo->id ?
                    'selected' : ''); ?>>
                    <?php echo e($metodo->nom_metodo); ?>

                    <!-- Corrección aquí -->
                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['metodo_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback" role="alert">
                <strong><?php echo e($message); ?></strong>
            </div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group mb-3">
            <label for="porcentaje_1" class="form-label"><?php echo e(__('Porcentaje 1')); ?></label>
            <input type="number" name="porcentaje_1" step="0.01" class="form-control <?php $__errorArgs = ['porcentaje_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('porcentaje_1', $porcentajesAhorro?->porcentaje_1)); ?>" id="porcentaje_1"
                placeholder="Ingrese porcentaje 1">
            <?php $__errorArgs = ['porcentaje_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        
        <div class="form-group mb-3">
            <label for="porcentaje_2" class="form-label"><?php echo e(__('Porcentaje 2')); ?></label>
            <input type="number" name="porcentaje_2" step="0.01" class="form-control <?php $__errorArgs = ['porcentaje_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('porcentaje_2', $porcentajesAhorro?->porcentaje_2)); ?>" id="porcentaje_2"
                placeholder="Ingrese porcentaje 2">
            <?php $__errorArgs = ['porcentaje_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        
        <div class="form-group mb-3">
            <label for="porcentaje_3" class="form-label"><?php echo e(__('Porcentaje 3')); ?></label>
            <input type="number" name="porcentaje_3" step="0.01" class="form-control <?php $__errorArgs = ['porcentaje_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('porcentaje_3', $porcentajesAhorro?->porcentaje_3)); ?>" id="porcentaje_3"
                placeholder="Ingrese porcentaje 3">
            <?php $__errorArgs = ['porcentaje_3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        
        <div class="form-group mb-3">
            <label for="porcentaje_4" class="form-label"><?php echo e(__('Porcentaje 4')); ?></label>
            <input type="number" name="porcentaje_4" step="0.01" class="form-control <?php $__errorArgs = ['porcentaje_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('porcentaje_4', $porcentajesAhorro?->porcentaje_4)); ?>" id="porcentaje_4"
                placeholder="Ingrese porcentaje 4">
            <?php $__errorArgs = ['porcentaje_4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary"><?php echo e(__('Submit')); ?></button>
    </div>
</div><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/porcentajes-ahorro/form.blade.php ENDPATH**/ ?>