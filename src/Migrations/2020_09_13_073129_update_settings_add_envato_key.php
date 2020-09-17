<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

class UpdateSettingsAddSharecodeKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = config('actcmsvn_sharecode.setting');
        $settingTable = (new $setting)->getTable();

        Schema::table($settingTable, function (Blueprint $table) use ($settingTable) {
            if (!Schema::hasColumn($settingTable, 'purchase_code')) {
                $table->string('purchase_code', 80)->nullable();
            }
        });

        $legalFile = storage_path() . '/legal';
        if (file_exists($legalFile)) {
            $legalFileInfo = File::get($legalFile);

            $legalFileInfo = explode('**', $legalFileInfo);
            $purchaseCode = $legalFileInfo[1];

            $setting = ($setting)::first();

            if ($setting) {
                $setting->purchase_code = $purchaseCode;
                $setting->save();
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
