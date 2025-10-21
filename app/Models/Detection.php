<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detection extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'id_zona',
    'clase',
    'fecha',
    'id_camara'
  ];

  protected $attribute = [
    'id_camara' => null,
  ];
}
