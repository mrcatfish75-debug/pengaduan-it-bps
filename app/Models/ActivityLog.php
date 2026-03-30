<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'aksi',
        'model',
        'model_id',
        'deskripsi',
        'ip_address',
        'user_agent',
    ];

    public static function record($aksi, $model = null, $modelId = null, $deskripsi = null)
    {
        self::create([
            'user_id'    => auth()->id(),
            'aksi'       => $aksi,
            'model'      => $model,
            'model_id'   => $modelId,
            'deskripsi'  => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}
}