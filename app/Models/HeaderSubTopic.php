<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderSubTopic extends Model
{
    /**
     * Tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'header_sub_topics';

    /**
     * Kolom yang dapat diisi (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Relasi dengan model SubTopic.
     * HeaderSubTopic memiliki banyak SubTopic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subTopics()
    {
        return $this->hasMany(SubTopic::class, 'id_header_sub_topic', 'id');
    }
}
