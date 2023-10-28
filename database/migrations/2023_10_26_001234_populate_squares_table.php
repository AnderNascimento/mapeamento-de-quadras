<?php

use App\Models\Square;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $squares = explode(
            "\n",
            file_get_contents(storage_path('app/squares.csv'))
        );

        foreach($squares as $square){
            $square = explode(',', $square);
            if(!isset($square[1])){
                continue;
            }

            $newSquare                 = new Square();
            $newSquare->user_id        = 1;
            $newSquare->name           = $square[0];
            $newSquare->polygon        = $square[1];
            $newSquare->starting_point = $square[2];
            $newSquare->save();
        }
    }

    public function down(): void
    {
        //
    }
};
