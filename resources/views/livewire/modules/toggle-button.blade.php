<div>
    @if($message)
        <div class="alert alert-{{ $status }} py-2 px-3 mb-2">{{ $message }}</div>
    @endif

    <button wire:click="sendCode" class="btn btn-sm {{ $module->status ? 'btn-danger' : 'btn-success' }}">
        <i class="fas {{ $module->status ? 'fa-ban' : 'fa-check' }}"></i>
        {{ $module->status ? 'Desactivar' : 'Activar' }}
    </button>

    @if($codeSent)
        <div class="mt-2 d-flex gap-2 align-items-center">
            <input type="text" wire:model.defer="verificationCode" maxlength="6" class="form-control form-control-sm" style="max-width:140px" placeholder="123456">
            <button wire:click="confirm" class="btn btn-sm btn-primary">
                <i class="fas fa-check"></i> Confirmar
            </button>
        </div>
    @endif
</div>


