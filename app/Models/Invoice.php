<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // Không sử dụng updated_at

    protected $fillable = [
        'user_id', 'payment_status', 'order_status', 'total', 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'invoice_id');
    }
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'invoice_id');

    }
}
