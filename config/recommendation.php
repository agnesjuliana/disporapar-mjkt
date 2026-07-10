<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bobot Rekomendasi Event
    |--------------------------------------------------------------------------
    |
    | S = (Bobot_Minat x Data_Profil) + (Bobot_Perilaku x Data_Klik)
    |
    | Data_Profil bernilai 1/0 (kategori event cocok dengan minat user atau
    | tidak). Data_Klik dinormalisasi 0-1 (jumlah klik user ke kategori
    | tsb dibagi jumlah klik terbanyak user ke kategori manapun).
    |
    */

    'bobot_minat' => (float) env('RECOMMENDATION_BOBOT_MINAT', 0.6),

    'bobot_perilaku' => (float) env('RECOMMENDATION_BOBOT_PERILAKU', 0.4),

];
