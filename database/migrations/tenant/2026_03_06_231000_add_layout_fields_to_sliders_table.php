<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (!Schema::hasColumn('sliders', 'slider_model')) {
                $table->string('slider_model')->default('full_width')->after('image');
            }

            if (!Schema::hasColumn('sliders', 'left_caption')) {
                $table->string('left_caption')->nullable()->after('button_url');
            }

            if (!Schema::hasColumn('sliders', 'right_top_image')) {
                $table->string('right_top_image')->nullable()->after('left_caption');
            }

            if (!Schema::hasColumn('sliders', 'right_top_caption')) {
                $table->string('right_top_caption')->nullable()->after('right_top_image');
            }

            if (!Schema::hasColumn('sliders', 'right_bottom_image')) {
                $table->string('right_bottom_image')->nullable()->after('right_top_caption');
            }

            if (!Schema::hasColumn('sliders', 'right_bottom_caption')) {
                $table->string('right_bottom_caption')->nullable()->after('right_bottom_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('sliders', 'slider_model')) {
                $dropColumns[] = 'slider_model';
            }

            if (Schema::hasColumn('sliders', 'left_caption')) {
                $dropColumns[] = 'left_caption';
            }

            if (Schema::hasColumn('sliders', 'right_top_image')) {
                $dropColumns[] = 'right_top_image';
            }

            if (Schema::hasColumn('sliders', 'right_top_caption')) {
                $dropColumns[] = 'right_top_caption';
            }

            if (Schema::hasColumn('sliders', 'right_bottom_image')) {
                $dropColumns[] = 'right_bottom_image';
            }

            if (Schema::hasColumn('sliders', 'right_bottom_caption')) {
                $dropColumns[] = 'right_bottom_caption';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
