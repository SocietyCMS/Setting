<?php

namespace Modules\Setting\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\Factory\useFactories;
use Modules\User\Entities\Entrust\EloquentRole;
use Modules\User\Entities\Entrust\EloquentUser;

class DemoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('setting__settings')->delete();
    }
}
