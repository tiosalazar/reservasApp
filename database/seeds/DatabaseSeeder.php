<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->call('PerfilesTableSeeder');
       $this->command->info('Perfiles table seeded!');
       $this->call('ZonasTableSeeder');
       $this->command->info('Zonas table seeded!');
       $this->call('PaisesTableSeeder');
       $this->command->info('Paises table seeded!');
       $this->call('DepartamentosTableSeeder');
       $this->command->info('Departamentos table seeded!');
       $this->call('CiudadesTableSeeder');
       $this->command->info('Ciudades table seeded!');
       $this->call('UsuariosTableSeeder');
       $this->command->info('Usuarios table seeded!');
    }

}

class PerfilesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();
        App\Role::create(array('name' => 'owner','display_name'=>'App Owner','description'=>'Usuario con todos los privilegios de la aplicación' ));
        App\Role::create(array('name' => 'desarrollo','display_name'=>'Test Profile','description'=>'Usuario con Privilegios para poder testear la Aplicación' ));
        App\Role::create(array('name' => 'administrador_cancha','display_name'=>'Administrador Cancha','description'=>'Usuario el cual tiene asignado una cancha y la puede admisnitrar.' ));
        App\Role::create(array('name' => 'usuario','display_name'=>'Usuario','description'=>'Usuario normal de la aplicación' ));
    }

}

class ZonasTableSeeder extends Seeder {

    public function run()
    {
        DB::table('zonas')->delete();

        App\Zona::create(array('nombre' => 'Sur'));
        App\Zona::create(array('nombre' => 'Norte') );
        App\Zona::create(array('nombre' => 'Centro') );
    }

}
class PaisesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('pais')->delete();

        App\Pais::create(array('nombre' => 'Colombia'));

    }

}
class DepartamentosTableSeeder extends Seeder {

    public function run()
    {
        DB::table('departamentos')->delete();

        App\Departamento::create(array('nombre' => 'Valle del Cauca'));
        App\Departamento::create(array('nombre' => 'Nariño') );
        App\Departamento::create(array('nombre' => 'Antioquia') );
    }

}
class CiudadesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('ciudades')->delete();

        App\Ciudade::create(array('nombre' => 'Cali','departamento_id' => 1));
        App\Ciudade::create(array('nombre' => 'Pasto','departamento_id' => 2) );
    }

}
class UsuariosTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();
        $rol= App\Role::where('name','desarrollo')->first();
        App\User::create(array('nombres' => 'Desarrollo','apellidos' => 'Querytek','celular'=>'3148220337','email'=>'david.salazar@querytek.com','api_token'=>'9wrFnCwQkWQfzW5AefRfA2lHHXvzLxO7nUtd8Y5EXyieIhsyLQbgBu3yN7GM','password'=>'$2y$10$jR5/cMT5Ov481GlouXvXAOg8NWaSJ/nJiECeo.AQcIcDS5GfZTLYq','roles_id'=>$rol['id'],'ciudad_id'=>1 ));
        $user =   App\User::where('email','david.salazar@querytek.com')->first();
        $user->attachRole($rol);
    }

}
