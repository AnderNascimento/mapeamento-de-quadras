<?php

use App\Models\Square;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $squares = explode(
            "\n",
            file_get_contents(storage_path('app/squares.csv'))
        );

        foreach($squares as $square){
            if (!isset($square[0])) {
                continue;
            }
            $square = explode(',', $square);

            $newSquare                 = new Square();
            $newSquare->user_id        = 1;
            $newSquare->name           = $square[0];
            $newSquare->polygon        = $square[1];
            $newSquare->starting_point = $square[2];
            dd($square);
            $newSquare->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
