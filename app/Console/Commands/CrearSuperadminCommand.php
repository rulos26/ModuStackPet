<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CrearSuperadminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modustack:crear-superadmin
                            {--name= : Nombre del Superadmin}
                            {--email= : Correo electrónico del Superadmin}
                            {--generar : Generar una contraseña aleatoria fuerte en lugar de pedirla}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea el primer usuario Superadmin sin contraseñas fijas en el código';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!Role::where('name', 'Superadmin')->exists()) {
            $this->error('❌ El rol "Superadmin" no existe. Ejecuta primero el seeder de roles (roleSeeder).');

            return Command::FAILURE;
        }

        $name = $this->option('name') ?: $this->ask('Nombre del Superadmin');
        $email = $this->option('email') ?: $this->ask('Correo electrónico del Superadmin');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error('❌ ' . $message);
            }

            return Command::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("❌ Ya existe un usuario con el correo {$email}. No se realizó ningún cambio.");

            return Command::FAILURE;
        }

        $generated = false;

        if ($this->option('generar')) {
            $password = $this->generarPasswordFuerte();
            $generated = true;
        } else {
            $password = $this->pedirPasswordOculta();

            if ($password === null) {
                return Command::FAILURE;
            }
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'email_verified_at' => now(),
            'activo' => true,
        ]);

        $user->assignRole('Superadmin');

        $this->info("✅ Superadmin creado: {$user->name} <{$user->email}>");

        if ($generated) {
            $this->warn('⚠️  Guarda esta contraseña ahora, no se volverá a mostrar:');
            $this->line($password);
        }

        return Command::SUCCESS;
    }

    /**
     * Pide la contraseña de forma oculta (dos veces, para confirmarla).
     * La contraseña nunca se acepta como argumento de línea de comandos.
     */
    protected function pedirPasswordOculta(): ?string
    {
        $password = $this->secret('Contraseña (mínimo 8 caracteres, no quedará en el historial)');
        $confirmacion = $this->secret('Confirma la contraseña');

        if ($password !== $confirmacion) {
            $this->error('❌ Las contraseñas no coinciden.');

            return null;
        }

        $validator = Validator::make(
            ['password' => $password],
            ['password' => ['required', 'string', 'min:8']]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error('❌ ' . $message);
            }

            return null;
        }

        return $password;
    }

    /**
     * Genera una contraseña aleatoria fuerte (mayúsculas, minúsculas, números y símbolos).
     */
    protected function generarPasswordFuerte(): string
    {
        $letrasMinusculas = Str::random(8);
        $letrasMayusculas = strtoupper(Str::random(8));
        $numeros = (string) random_int(1000, 9999);
        $simbolos = collect(['!', '@', '#', '$', '%', '&', '*'])->random(2)->implode('');

        $caracteres = str_split($letrasMinusculas . $letrasMayusculas . $numeros . $simbolos);
        shuffle($caracteres);

        return implode('', $caracteres);
    }
}
