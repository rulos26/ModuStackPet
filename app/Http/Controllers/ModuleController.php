<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\ModuleLog;
use App\Models\ModuleVerification;
use App\Mail\ModuleVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Module::class);

        $query = Module::query();

        if ($request->filled('q')) {
            $q = trim($request->string('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        $modules = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('modules.index', compact('modules'));
    }

    public function requestToggleStatus(Request $request, Module $module)
    {
        $this->authorize('update', $module);

        $nuevoEstado = !$module->status;
        $action = $nuevoEstado ? 'activar' : 'desactivar';

        // Create verification record
        $verification = ModuleVerification::createForModule(
            Auth::id(),
            $module->id,
            $action
        );

        // Send verification email
        try {
            Mail::to(Auth::user()->email)->send(
                new ModuleVerificationMail(
                    $verification->verification_code,
                    $module->name,
                    $action
                )
            );

            // Log verification sent
            ModuleLog::createLog(
                Auth::id(),
                $module->id,
                ModuleLog::ACTION_VERIFICATION_SENT,
                $request->ip(),
                $request->userAgent()
            );

            Log::info('Código de verificación enviado para cambio de estado de módulo', [
                'module' => $module->slug,
                'action' => $action,
                'user_id' => Auth::id(),
                'verification_id' => $verification->id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Código enviado a tu correo. Ingresa el código para confirmar.',
                    'module' => $module->only(['id','name','slug']),
                ]);
            }

            return back()->with('info', 'Se ha enviado un código de verificación a tu correo electrónico. Úsalo para confirmar la acción.');
        } catch (\Exception $e) {
            Log::error('Error enviando código de verificación', [
                'module' => $module->slug,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Error enviando el código de verificación. Intenta nuevamente.');
        }
    }

    public function confirmToggleStatus(Request $request, Module $module)
    {
        $this->authorize('update', $module);

        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $verification = ModuleVerification::findByCode(
            $request->verification_code,
            Auth::id()
        );

        if (!$verification || $verification->module_id !== $module->id) {
            // Log failed verification attempt
            ModuleLog::createLog(
                Auth::id(),
                $module->id,
                ModuleLog::ACTION_VERIFICATION_FAILED,
                $request->ip(),
                $request->userAgent()
            );

            Log::warning('Código de verificación inválido para cambio de estado de módulo', [
                'module' => $module->slug,
                'user_id' => Auth::id(),
                'code' => $request->verification_code,
                'ip' => $request->ip(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'message' => 'Código inválido o expirado.'], 422);
            }
            return back()->with('error', 'Código de verificación inválido o expirado.');
        }

        $nuevoEstado = !$module->status;

        DB::transaction(function () use ($module, $nuevoEstado, $request, $verification) {
            // Update module status
            $module->update(['status' => $nuevoEstado]);

            // Log the action using ModuleLog model
            ModuleLog::createLog(
                Auth::id(),
                $module->id,
                $nuevoEstado ? ModuleLog::ACTION_ACTIVATED : ModuleLog::ACTION_DEACTIVATED,
                $request->ip(),
                $request->userAgent()
            );

            // Mark verification as used
            $verification->markAsUsed();
        });

        Log::info('Estado de módulo actualizado con verificación', [
            'module' => $module->slug,
            'status' => $nuevoEstado,
            'user_id' => Auth::id(),
            'verification_id' => $verification->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Módulo ' . $module->name . ' ' . ($nuevoEstado ? 'activado' : 'desactivado') . ' correctamente.',
                'status' => $nuevoEstado,
            ]);
        }
        return back()->with('success', 'Módulo ' . $module->name . ' ' . ($nuevoEstado ? 'activado' : 'desactivado') . ' correctamente.');
    }

    public function showVerificationForm(Module $module)
    {
        $this->authorize('viewAny', Module::class);

        return view('modules.verification', compact('module'));
    }

    public function showLogs(Module $module)
    {
        $this->authorize('viewAny', Module::class);

        $logs = ModuleLog::getModuleLogs($module->id, 50);

        return view('modules.logs', compact('module', 'logs'));
    }

    public function showAllLogs()
    {
        $this->authorize('viewAny', Module::class);

        $logs = ModuleLog::getRecentLogs(100);
        $accessDeniedLogs = ModuleLog::getAccessDeniedLogs(20);
        $failedVerificationLogs = ModuleLog::getFailedVerificationLogs(20);

        return view('modules.all-logs', compact('logs', 'accessDeniedLogs', 'failedVerificationLogs'));
    }

    // Autorización basada en políticas (ModulePolicy)
}



