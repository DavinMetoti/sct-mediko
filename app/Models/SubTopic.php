<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubTopic extends Model
{
    /**
     * Tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'sub_topics';

    /**
     * Kolom yang dapat diisi (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'id_header_sub_topic',
        'name',
        'description',
        'path',
    ];

    /**
     * Relasi ke HeaderSubTopic.
     * SubTopic dimiliki oleh satu HeaderSubTopic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function headerSubTopic()
    {
        return $this->belongsTo(HeaderSubTopic::class, 'id_header_sub_topic', 'id');
    }
}
