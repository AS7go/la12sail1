<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //Импортирует трейт SoftDeletes в модель


class Post extends Model
{
    use HasFactory, SoftDeletes; // Используется трейт SoftDeletes

    protected $table = 'posts'; // Имя таблицы (posts) в БД, с которой связана модель Post

    protected $fillable = [
        'name',
        'text',
    ];
}