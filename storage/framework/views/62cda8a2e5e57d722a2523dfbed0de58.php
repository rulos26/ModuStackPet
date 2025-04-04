<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="nom_metodo" class="form-label"><?php echo e(__('Nom Metodo')); ?></label>
            <input type="text" name="nom_metodo" class="form-control <?php $__errorArgs = ['nom_metodo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('nom_metodo', $metodosAhorro?->nom_metodo)); ?>" id="nom_metodo" placeholder="Nom Metodo">
            <?php echo $errors->first('nom_metodo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>'); ?>

        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label"><?php echo e(__('Descripcion')); ?></label>
            <input type="text" name="descripcion" class="form-control <?php $__errorArgs = ['descripcion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('descripcion', $metodosAhorro?->descripcion)); ?>" id="descripcion" placeholder="Descripcion">
            <?php echo $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>'); ?>

        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary"><?php echo e(__('Submit')); ?></button>
    </div>
</div><?php /**PATH C:\xampp\htdocs\ModuStack\ModuStack\ModuStack\resources\views/metodos-ahorro/form.blade.php ENDPATH**/ ?>