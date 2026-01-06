namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'information'];
    
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class);
    }
}