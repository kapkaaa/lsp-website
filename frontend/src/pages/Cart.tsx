import React, { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import {
  TrashIcon,
  ShoppingBagIcon,
  MinusIcon,
  PlusIcon,
} from '@heroicons/react/24/outline';
import apiClient from '../services/apiClient';
import Loading from '../components/Loading';
import Button from '../components/Button';
import { formatCurrency } from '../utils/formatters';
import { useUser } from '../contexts/UserContext';
import { alert as swal } from '../utils/swal';

interface CartItem {
  id: number;
  quantity: number;
  product_detail: {
    id: number;
    stock: number;
    color: { name: string };
    size: { name: string };
    photos: Array<{ photo_url: string }>;
    product: {
      id: number;
      name: string;
      selling_price: number;
      brand: { name: string };
      type: { name: string };
    };
  };
}

const Cart: React.FC = () => {
  const navigate = useNavigate();
  const { user, isLoading } = useUser();
  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [updating, setUpdating] = useState<number | null>(null);
  const [selectedItems, setSelectedItems] = useState<Set<number>>(new Set());

  useEffect(() => {
    if (!isLoading) {
      if (!user) {
        navigate('/login');
        return;
      }
      fetchCart();
    }
  }, [user, isLoading]);

  // Auto-select all items when cart is loaded
  useEffect(() => {
    if (cartItems.length > 0) {
      setSelectedItems(new Set(cartItems.map(item => item.id)));
    }
  }, [cartItems]);

  const fetchCart = async () => {
    try {
      const response = await apiClient.get('/cart');
      const data = response.data.data;
      setCartItems(Array.isArray(data) ? data : (data?.items || []));
    } catch (error) {
      console.error('Failed to fetch cart:', error);
    } finally {
      setLoading(false);
    }
  };

  const toggleSelectItem = (itemId: number) => {
    setSelectedItems(prev => {
      const newSet = new Set(prev);
      if (newSet.has(itemId)) {
        newSet.delete(itemId);
      } else {
        newSet.add(itemId);
      }
      return newSet;
    });
  };

  const toggleSelectAll = () => {
    if (selectedItems.size === cartItems.length) {
      setSelectedItems(new Set());
    } else {
      setSelectedItems(new Set(cartItems.map(item => item.id)));
    }
  };

  const isAllSelected = cartItems.length > 0 && selectedItems.size === cartItems.length;

  const updateQuantity = async (itemId: number, newQuantity: number) => {
    const item = cartItems.find(i => i.id === itemId);
    if (!item) return;

    const productDetail = item.product_detail;
    if (!productDetail) return;

    if (newQuantity > (productDetail.stock || 0)) {
      swal.warning('Stok Terbatas', `Stok hanya tersedia ${productDetail.stock} pcs`);
      return;
    }

    if (newQuantity < 1) return;

    setUpdating(itemId);
    try {
      await apiClient.put(`/cart/${itemId}`, { quantity: newQuantity });
      setCartItems(cartItems.map(i =>
        i.id === itemId ? { ...i, quantity: newQuantity } : i
      ));
    } catch (error) {
      console.error('Failed to update quantity:', error);
      swal.error('Gagal!', 'Gagal mengupdate jumlah');
    } finally {
      setUpdating(null);
    }
  };

  const removeItem = async (itemId: number) => {
    const result = await swal.confirm('Hapus Item?', 'Apakah Anda yakin ingin menghapus item ini dari keranjang?');
    if (!result.isConfirmed) return;

    setUpdating(itemId);
    try {
      await apiClient.delete(`/cart/${itemId}`);
      setCartItems(cartItems.filter(i => i.id !== itemId));
      setSelectedItems(prev => {
        const newSet = new Set(prev);
        newSet.delete(itemId);
        return newSet;
      });
    } catch (error) {
      console.error('Failed to remove item:', error);
      swal.error('Gagal!', 'Gagal menghapus item');
    } finally {
      setUpdating(null);
    }
  };

  if (loading || isLoading) {
    return <Loading text="Memuat keranjang..." />;
  }

  // Calculate subtotal only for selected items
  const selectedCartItems = cartItems.filter(item => selectedItems.has(item.id));
  const subtotal = selectedCartItems.reduce(
    (sum, item) => sum + (item.product_detail?.product?.selling_price || 0) * item.quantity,
    0
  );
  const totalSelectedItems = selectedCartItems.reduce((sum, item) => sum + item.quantity, 0);

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="container-custom">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-heading font-bold text-gray-900 mb-2">
            Keranjang Belanja
          </h1>
          <p className="text-gray-600">
            {cartItems.length} item dalam keranjang Anda
          </p>
        </div>

        {cartItems.length === 0 ? (
          /* Empty Cart State */
          <div className="max-w-md mx-auto text-center py-16">
            <div className="mb-6">
              <ShoppingBagIcon className="h-24 w-24 mx-auto text-gray-300" />
            </div>
            <h2 className="text-2xl font-heading font-semibold text-gray-900 mb-2">
              Keranjang Kosong
            </h2>
            <p className="text-gray-600 mb-6">
              Belum ada produk di keranjang Anda. Mulai belanja sekarang!
            </p>
            <Button onClick={() => navigate('/products')} variant="primary">
              Mulai Belanja
            </Button>
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Cart Items */}
            <div className="lg:col-span-2 space-y-4">
              {/* Select All Header */}
              <div className="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4">
                <label className="flex items-center gap-3 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={isAllSelected}
                    onChange={toggleSelectAll}
                    className="w-5 h-5 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500 cursor-pointer"
                  />
                  <span className="font-medium text-gray-900">
                    Pilih Semua ({cartItems.length} item)
                  </span>
                </label>
                <span className="ml-auto text-sm text-gray-500">
                  {selectedItems.size} item dipilih
                </span>
              </div>

              {cartItems.map((item) => (
                <div
                  key={item.id}
                  className={`bg-white rounded-xl shadow-sm p-6 animate-fade-in transition-all ${selectedItems.has(item.id) ? 'ring-2 ring-cyan-500' : ''}`}
                >
                  <div className="flex gap-4">
                    {/* Checkbox */}
                    <div className="flex items-start pt-1">
                      <input
                        type="checkbox"
                        checked={selectedItems.has(item.id)}
                        onChange={() => toggleSelectItem(item.id)}
                        className="w-5 h-5 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500 cursor-pointer"
                      />
                    </div>

                    {/* Product Image */}
                    <div className="flex-shrink-0">
                      <img
                        src={item.product_detail?.photos?.[0]?.photo_url || '/placeholder-tshirt.jpg'}
                        alt={item.product_detail?.product?.name}
                        className="w-28 h-28 object-cover rounded-lg"
                      />
                    </div>

                    {/* Product Info */}
                    <div className="flex-1 min-w-0">
                      <Link
                        to={`/product/${item.product_detail?.product?.id}`}
                        className="block group"
                      >
                        <h3 className="text-lg font-semibold text-gray-900 group-hover:text-cyan-600 transition-colors">
                          {item.product_detail?.product?.name}
                        </h3>
                      </Link>
                      <p className="text-sm text-gray-600 mt-1">
                        {item.product_detail?.product?.brand?.name} • {item.product_detail?.product?.type?.name}
                      </p>
                      <div className="flex items-center gap-4 mt-2">
                        <span className="text-sm text-gray-600">
                          Warna: <span className="font-medium">{item.product_detail?.color?.name}</span>
                        </span>
                        <span className="text-sm text-gray-600">
                          Ukuran: <span className="font-medium">{item.product_detail?.size?.name}</span>
                        </span>
                      </div>
                      <p className="text-sm text-gray-500 mt-1">
                        Stok: {item.product_detail?.stock}
                      </p>
                    </div>

                    {/* Price & Actions */}
                    <div className="flex flex-col items-end justify-between">
                      <p className="text-xl font-bold text-cyan-600">
                        {formatCurrency((item.product_detail?.product?.selling_price || 0) * item.quantity)}
                      </p>
                      <div className="text-sm text-gray-500">
                        {formatCurrency(item.product_detail?.product?.selling_price || 0)} / pcs
                      </div>

                      {/* Quantity Controls */}
                      <div className="flex items-center gap-2 mt-4">
                        <button
                          onClick={() => updateQuantity(item.id, item.quantity - 1)}
                          disabled={updating === item.id || item.quantity <= 1}
                          className="p-1.5 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                          <MinusIcon className="h-4 w-4 text-gray-600" />
                        </button>
                        <span className="w-12 text-center font-semibold">
                          {item.quantity}
                        </span>
                        <button
                          onClick={() => updateQuantity(item.id, item.quantity + 1)}
                          disabled={updating === item.id || item.quantity >= (item.product_detail?.stock || 0)}
                          className="p-1.5 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                          <PlusIcon className="h-4 w-4 text-gray-600" />
                        </button>
                      </div>

                      {/* Remove Button */}
                      <button
                        onClick={() => removeItem(item.id)}
                        disabled={updating === item.id}
                        className="mt-4 text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-1 disabled:opacity-50"
                      >
                        <TrashIcon className="h-4 w-4" />
                        Hapus
                      </button>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Order Summary */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h2 className="text-xl font-heading font-semibold text-gray-900 mb-6">
                  Ringkasan Belanja
                </h2>

                <div className="space-y-4 mb-6">
                  <div className="flex justify-between text-gray-600">
                    <span>Subtotal ({totalSelectedItems} item)</span>
                    <span className="font-semibold">{formatCurrency(subtotal)}</span>
                  </div>

                  <div className="flex justify-between text-sm text-gray-500">
                    <span>Ongkos Kirim</span>
                    <span>Dihitung di checkout</span>
                  </div>
                </div>

                <div className="border-t border-gray-200 pt-4 mb-6">
                  <div className="flex justify-between text-lg font-bold text-gray-900">
                    <span>Total</span>
                    <span className="text-cyan-600">{formatCurrency(subtotal)}</span>
                  </div>
                  <p className="text-xs text-gray-500 mt-1">
                    Belum termasuk ongkos kirim
                  </p>
                </div>

                <Button
                  onClick={() => {
                    // Save selected items to sessionStorage for checkout
                    sessionStorage.setItem('selectedCartItems', JSON.stringify(Array.from(selectedItems)));
                    navigate('/checkout');
                  }}
                  variant="primary"
                  className="w-full mb-3"
                  size="lg"
                  disabled={selectedItems.size === 0}
                >
                  Lanjut ke Checkout ({selectedItems.size})
                </Button>

                <Button
                  onClick={() => navigate('/products')}
                  variant="secondary"
                  className="w-full"
                >
                  Lanjut Belanja
                </Button>

                {/* Info */}
                <div className="mt-6 p-4 bg-cyan-50 rounded-lg">
                  <p className="text-sm text-cyan-800">
                    💡 Pengiriman akan diproses setelah pembayaran terverifikasi
                  </p>
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default Cart;