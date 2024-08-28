<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'login_location_id');
    }

    public function dailyReport()
    {
        return $this->hasOne(DailyReport::class)->where('current_date', Carbon::now()->toDateString());
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function createdOrders()
    {
        return $this->hasMany(Order::class, 'created_by', 'id');
    }

    public function initiatedPayments()
    {
        return $this->hasMany(Invoice::class, 'initiated_by', 'id');
    }

    public function receivedPayments()
    {
        return $this->hasMany(Payment::class, 'receiving_cashier', 'id');
    }

    public function initiatedPayment()
    {
        return $this->hasOne(Payment::class, 'initiated_by', 'id');
    }

    public function uploadedStockProducts()
    {
        return $this->hasMany(StockProduct::class, 'uploaded_by', 'id');
    }

    public function updateLoginLocation($locationId)
    {
        $this->login_location_id = $locationId;
        $this->save();
    }
}
