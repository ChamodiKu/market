<?php

namespace App\Models;

/**
 * Created by Reliese Model
 */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\Collection;
use Carbon\Carbon;

/**
 *
 * Class Seller
 *
 * @property int $id
 * @property string $seller_name
 * @property string $email
 * @property Carbon|null $email_varified_at
 * @property string $password
 * @property int $active_status
 * @property Carbon|null $created_at
 * @property Carbon|null $modified_at
 *
 * @property Seller $seller
 * @property Collection|Seller[] $sellers
 *
 * @property Collection|ApiAccount[] $apiAccounts
 *
 * @package App\Models
 */

class Seller extends Authenticatable
{
    protected $table = 'sellers';
    public $timestamps = false;

    protected $casts = [
        'active_status' => 'int'
    ];

    protected $dates = [
        'email_verified_at',
        'modified_at'
    ];

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'seller_name',
        'email',
        'email_verified_at',
        'password',
        'active_status',
        'modified_at'
    ];
}
