<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model{
public function receipt()
{
    return $this->hasOne(RepairReceipt::class);
}
}
