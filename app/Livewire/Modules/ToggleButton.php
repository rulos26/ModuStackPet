<?php

namespace App\Livewire\Modules;

use App\Mail\ModuleVerificationMail;
use App\Models\Module;
use App\Models\ModuleLog;
use App\Models\ModuleVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class ToggleButton extends Component
{
    public Module $module;
    public string $verificationCode = '';
    public bool $codeSent = false;
    public string $message = '';
    public string $status = '';

    public function mount(Module $module): void
    {
        $this->module = $module;
    }

    public function sendCode(): void
    {
        $this->authorizeAction();

        $nuevoEstado = !$this->module->status;
        $action = $nuevoEstado ? 'activar' : 'desactivar';

        $verification = ModuleVerification::createForModule(Auth::id(), $this->module->id, $action);

        try {
            Mail::to(Auth::user()->email)->send(new ModuleVerificationMail(
                $verification->verification_code,
                $this->module->name,
                $action
            ));

            ModuleLog::createLog(
                Auth::id(),
                $this->module->id,
                ModuleLog::ACTION_VERIFICATION_SENT,
                request()->ip(),
                request()->userAgent()
            );

            $this->codeSent = true;
            $this->status = 'info';
            $this->message = 'Se envió un código de verificación a tu correo.';
        } catch (\Exception $e) {
            Log::error('Error enviando código de verificación', [
                'module' => $this->module->slug,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            $this->status = 'danger';
            $this->message = 'Error enviando el código. Intenta de nuevo.';
        }
    }

    public function confirm(): void
    {
        $this->authorizeAction();

        $this->validate([
            'verificationCode' => ['required', 'string', 'size:6'],
        ]);

        $verification = ModuleVerification::findByCode($this->verificationCode, Auth::id());
        if (!$verification || $verification->module_id !== $this->module->id) {
            ModuleLog::createLog(
                Auth::id(),
                $this->module->id,
                ModuleLog::ACTION_VERIFICATION_FAILED,
                request()->ip(),
                request()->userAgent()
            );
            $this->status = 'danger';
            $this->message = 'Código inválido o expirado.';
            return;
        }

        $nuevoEstado = !$this->module->status;

        DB::transaction(function () use ($nuevoEstado, $verification) {
            $this->module->update(['status' => $nuevoEstado]);
            ModuleLog::createLog(
                Auth::id(),
                $this->module->id,
                $nuevoEstado ? ModuleLog::ACTION_ACTIVATED : ModuleLog::ACTION_DEACTIVATED,
                request()->ip(),
                request()->userAgent()
            );
            $verification->markAsUsed();
        });

        $this->status = 'success';
        $this->message = 'Módulo ' . $this->module->name . ' ' . ($nuevoEstado ? 'activado' : 'desactivado') . ' correctamente.';
        $this->codeSent = false;
        $this->verificationCode = '';

        // Refrescar datos del módulo
        $this->module->refresh();
        $this->dispatch('module-status-updated', id: $this->module->id, status: $this->module->status);
    }

    private function authorizeAction(): void
    {
        abort_unless(Auth::check(), 401);
        $this->authorize('update', $this->module);
    }

    public function render()
    {
        return view('livewire.modules.toggle-button');
    }
}


