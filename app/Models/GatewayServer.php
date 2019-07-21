<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayServer extends Model
{
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'gateway_server';
    }

    protected $hidden = ['created_at', 'updated_at'];
}