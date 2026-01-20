import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import {
  MapPinIcon,
  CreditCardIcon,
  TruckIcon,
  PhotoIcon,
  CheckCircleIcon,
  DocumentDuplicateIcon as ClipboardIcon,
} from '@heroicons/react/24/outline';
import Modal from '../components/Modal';
import apiClient from '../services/apiClient';
import Loading from '../components/Loading';
import Button from '../components/Button';
import { formatCurrency } from '../utils/formatters';
import { calculateWeight, calculateShippingCost } from '../utils/shippingCalculator';
import { useUser } from '../contexts/UserContext';

interface CartItem {
  id: number;
  quantity: number;
  product_detail: {
    product: {
      name: string;
      selling_price: number;
    };
  };
}

interface ShippingRate {
  id: number;
  region: string;
  price_per_kg: number;
}

// Hardcoded regions removed. Now using dynamic data from shippingRates API.

const Checkout: React.FC = () => {
  const navigate = useNavigate();
  const { user, isLoading } = useUser();

  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [shippingRates, setShippingRates] = useState<ShippingRate[]>([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);

  // Form states
  const [destinationCity, setDestinationCity] = useState('');
  const [address, setAddress] = useState('');
  const [paymentMethod, setPaymentMethod] = useState('transfer');
  const [selectedBank, setSelectedBank] = useState<'bca' | 'mandiri' | null>(null);
  const [showQrisModal, setShowQrisModal] = useState(false);
  const [paymentProof, setPaymentProof] = useState<File | null>(null);
  const [previewUrl, setPreviewUrl] = useState<string>('');

  useEffect(() => {
    if (!isLoading) {
      if (!user) {
        navigate('/login');
        return;
      }
      fetchCheckoutData();
    }
  }, [user, isLoading]);

  const fetchCheckoutData = async () => {
    try {
      const [cartResponse, ratesResponse] = await Promise.all([
        apiClient.get('/cart'),
        // Fetch shipping rates if available, otherwise use hardcoded data
        apiClient.get('/shipping-rates').catch(() => ({ data: { data: [] } })),
      ]);

      const data = cartResponse.data.data;
      const allItems = Array.isArray(data) ? data : (data?.items || []);

      // Filter items based on selection from cart page
      const selectedIds = JSON.parse(sessionStorage.getItem('selectedCartItems') || '[]');
      if (selectedIds.length > 0) {
        setCartItems(allItems.filter((item: any) => selectedIds.includes(item.id)));
      } else {
        setCartItems(allItems); // Fallback to all items if no selection found
      }

      // If backend doesn't provide rates, use hardcoded data (matched with seeder)
      if (ratesResponse.data.data.length === 0) {
        setShippingRates([
          { id: 1, region: 'Jakarta', price_per_kg: 10000 },
          { id: 3, region: 'Depok', price_per_kg: 24000 },
          { id: 4, region: 'Bekasi', price_per_kg: 25000 },
          { id: 5, region: 'Tangerang', price_per_kg: 25000 },
          { id: 6, region: 'Bogor', price_per_kg: 27000 },
          { id: 7, region: 'Jawa Barat', price_per_kg: 31000 },
          { id: 8, region: 'Jawa Tengah', price_per_kg: 39000 },
          { id: 9, region: 'Jawa Timur', price_per_kg: 47000 },
        ]);
      } else {
        setShippingRates(ratesResponse.data.data);
      }
    } catch (error) {
      console.error('Failed to fetch checkout data:', error);
    } finally {
      setLoading(false);
    }
  };

  const copyToClipboard = (text: string) => {
    navigator.clipboard.writeText(text);
    alert('Nomor rekening berhasil disalin!');
  };

  const handlePaymentProofChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setPaymentProof(file);
      const reader = new FileReader();
      reader.onloadend = () => {
        setPreviewUrl(reader.result as string);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!destinationCity || !address) {
      alert('Mohon lengkapi alamat pengiriman');
      return;
    }

    if (!paymentProof) {
      alert('Mohon upload bukti pembayaran');
      return;
    }

    setSubmitting(true);
    try {
      const formData = new FormData();
      formData.append('destination_city', destinationCity);
      formData.append('shipping_address', address);
      formData.append('payment_method', paymentMethod);
      formData.append('payment_proof', paymentProof);

      const selectedRate = shippingRates.find(r => r.region === destinationCity);
      if (selectedRate) {
        formData.append('shipping_rate_id', selectedRate.id.toString());
      }

      await apiClient.post('/orders', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      alert('Pesanan berhasil dibuat! Silakan tunggu verifikasi dari kasir.');
      navigate('/orders');
    } catch (error) {
      console.error('Failed to create order:', error);
      alert('Gagal membuat pesanan. Silakan coba lagi.');
    } finally {
      setSubmitting(false);
    }
  };

  if (loading || isLoading) {
    return <Loading text="Memuat checkout..." />;
  }

  if (cartItems.length === 0) {
    navigate('/cart');
    return null;
  }

  const subtotal = cartItems.reduce(
    (sum, item) => sum + (item.product_detail?.product?.selling_price || 0) * item.quantity,
    0
  );

  const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);

  const selectedRate = shippingRates.find(r => r.region === destinationCity);
  const weight = calculateWeight(totalItems);
  const shippingCost = selectedRate ? calculateShippingCost(totalItems, selectedRate.price_per_kg) : 0;
  const total = subtotal + shippingCost;

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="container-custom max-w-6xl">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-heading font-bold text-gray-900 mb-2">
            Checkout
          </h1>
          <p className="text-gray-600">
            Lengkapi data pengiriman dan pembayaran
          </p>
        </div>

        <form onSubmit={handleSubmit}>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Main Form */}
            <div className="lg:col-span-2 space-y-6">
              {/* Shipping Address */}
              <div className="bg-white rounded-xl shadow-sm p-6">
                <div className="flex items-center gap-3 mb-6">
                  <div className="p-2 bg-cyan-100 rounded-lg">
                    <MapPinIcon className="h-6 w-6 text-cyan-600" />
                  </div>
                  <h2 className="text-xl font-heading font-semibold text-gray-900">
                    Alamat Pengiriman
                  </h2>
                </div>

                <div className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Wilayah Tujuan *
                    </label>
                    <select
                      value={destinationCity}
                      onChange={(e) => setDestinationCity(e.target.value)}
                      className="input"
                      required
                    >
                      <option value="">Pilih Wilayah</option>
                      {shippingRates.map((rate) => (
                        <option key={rate.id} value={rate.region}>
                          {rate.region}
                        </option>
                      ))}
                    </select>
                    {selectedRate && (
                      <p className="mt-2 text-sm text-gray-600">
                        Ongkir: {formatCurrency(selectedRate.price_per_kg)}/kg
                      </p>
                    )}
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Alamat Lengkap *
                    </label>
                    <textarea
                      value={address}
                      onChange={(e) => setAddress(e.target.value)}
                      className="input min-h-[100px]"
                      placeholder="Masukkan alamat lengkap pengiriman..."
                      required
                    />
                  </div>
                </div>
              </div>

              {/* Shipping Info */}
              {destinationCity && (
                <div className="bg-cyan-50 border border-cyan-200 rounded-xl p-6">
                  <div className="flex items-start gap-3">
                    <TruckIcon className="h-6 w-6 text-cyan-600 flex-shrink-0 mt-1" />
                    <div className="flex-1">
                      <h3 className="font-semibold text-cyan-900 mb-2">
                        Informasi Pengiriman
                      </h3>
                      <div className="space-y-1 text-sm text-cyan-800">
                        <p>• Total Item: {totalItems} pcs</p>
                        <p>• Berat: {weight} kg (3 pcs = 1 kg)</p>
                        <p>• Biaya Ongkir: {formatCurrency(shippingCost)}</p>
                        <p className="text-xs text-cyan-600 mt-2">
                          * Pengiriman diproses setelah pembayaran terverifikasi
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* Payment Method */}
              <div className="bg-white rounded-xl shadow-sm p-6">
                <div className="flex items-center gap-3 mb-6">
                  <div className="p-2 bg-cyan-100 rounded-lg">
                    <CreditCardIcon className="h-6 w-6 text-cyan-600" />
                  </div>
                  <h2 className="text-xl font-heading font-semibold text-gray-900">
                    Metode Pembayaran
                  </h2>
                </div>

                <div className="space-y-3">
                  <label className="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-cyan-500 transition-colors">
                    <input
                      type="radio"
                      name="payment"
                      value="transfer"
                      checked={paymentMethod === 'transfer'}
                      onChange={(e) => setPaymentMethod(e.target.value)}
                      className="text-cyan-600 focus:ring-cyan-500"
                    />
                    <div className="flex-1">
                      <p className="font-medium text-gray-900">Transfer Bank</p>
                      <p className="text-sm text-gray-600">
                        BCA / Mandiri / BRI
                      </p>
                    </div>
                  </label>

                  <label className="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-cyan-500 transition-colors">
                    <input
                      type="radio"
                      name="payment"
                      value="qris"
                      checked={paymentMethod === 'qris'}
                      onChange={(e) => setPaymentMethod(e.target.value)}
                      className="text-cyan-600 focus:ring-cyan-500"
                    />
                    <div className="flex-1">
                      <p className="font-medium text-gray-900">QRIS</p>
                      <p className="text-sm text-gray-600">
                        Scan QR untuk bayar
                      </p>
                    </div>
                  </label>
                </div>

                {/* Bank Transfer Details */}
                {paymentMethod === 'transfer' && (
                  <div className="mt-6 space-y-4 animate-fade-in">
                    <p className="text-sm font-medium text-gray-700">Pilih Rekening Bank:</p>
                    <div className="grid grid-cols-2 gap-4">
                      <button
                        type="button"
                        onClick={() => setSelectedBank('bca')}
                        className={`p-4 border-2 rounded-xl text-center transition-all ${selectedBank === 'bca'
                          ? 'border-cyan-500 bg-cyan-50'
                          : 'border-gray-100 hover:border-gray-200'
                          }`}
                      >
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" className="h-6 mx-auto mb-2" />
                        <span className="text-sm font-semibold">BCA</span>
                      </button>
                      <button
                        type="button"
                        onClick={() => setSelectedBank('mandiri')}
                        className={`p-4 border-2 rounded-xl text-center transition-all ${selectedBank === 'mandiri'
                          ? 'border-cyan-500 bg-cyan-50'
                          : 'border-gray-100 hover:border-gray-200'
                          }`}
                      >
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri" className="h-6 mx-auto mb-2" />
                        <span className="text-sm font-semibold">Mandiri</span>
                      </button>
                    </div>

                    {selectedBank && (
                      <div className="bg-gray-50 rounded-xl p-4 border border-gray-200 animate-fade-in">
                        <div className="flex justify-between items-start">
                          <div>
                            <p className="text-xs text-gray-500 uppercase tracking-wider mb-1">Nomor Rekening</p>
                            <p className="text-lg font-bold text-gray-900 font-mono">
                              {selectedBank === 'bca' ? '1234567890' : '0987654321'}
                            </p>
                            <p className="text-sm text-gray-600 mt-1">a/n DistroZone Admin</p>
                          </div>
                          <button
                            type="button"
                            onClick={() => copyToClipboard(selectedBank === 'bca' ? '1234567890' : '0987654321')}
                            className="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition-colors"
                            title="Salin No. Rekening"
                          >
                            <ClipboardIcon className="h-5 w-5" />
                          </button>
                        </div>
                      </div>
                    )}
                  </div>
                )}

                {/* QRIS Logic */}
                {paymentMethod === 'qris' && (
                  <div className="mt-6 animate-fade-in">
                    <Button
                      type="button"
                      variant="outline"
                      className="w-full"
                      onClick={() => setShowQrisModal(true)}
                    >
                      <PhotoIcon className="h-5 w-5" />
                      Lihat Kode QRIS
                    </Button>
                  </div>
                )}

                {/* Payment Info */}
                <div className="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                  <p className="text-sm text-yellow-800 font-medium mb-2">
                    Informasi Pembayaran
                  </p>
                  <p className="text-sm text-yellow-700">
                    Silakan lakukan pembayaran sebesar <span className="font-bold">{formatCurrency(total)}</span> dan upload bukti pembayaran di bawah ini.
                  </p>
                </div>

                {/* Payment Proof Upload */}
                <div className="mt-6">
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Upload Bukti Pembayaran *
                  </label>
                  <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-cyan-500 transition-colors">
                    <input
                      type="file"
                      accept="image/*"
                      onChange={handlePaymentProofChange}
                      className="hidden"
                      id="payment-proof"
                      required
                    />
                    <label htmlFor="payment-proof" className="cursor-pointer">
                      {previewUrl ? (
                        <div>
                          <img
                            src={previewUrl}
                            alt="Preview"
                            className="max-h-48 mx-auto rounded-lg mb-3"
                          />
                          <p className="text-sm text-cyan-600 font-medium">
                            Klik untuk ganti gambar
                          </p>
                        </div>
                      ) : (
                        <div>
                          <PhotoIcon className="h-12 w-12 mx-auto text-gray-400 mb-3" />
                          <p className="text-gray-600 font-medium mb-1">
                            Klik untuk upload bukti bayar
                          </p>
                          <p className="text-sm text-gray-500">JPG, PNG (Max 5MB)</p>
                        </div>
                      )}
                    </label>
                  </div>
                </div>
              </div>
            </div>

            {/* Order Summary */}
            <div className="lg:col-span-1">
              <div className="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h2 className="text-xl font-heading font-semibold text-gray-900 mb-6">
                  Ringkasan Pesanan
                </h2>

                <div className="space-y-4 mb-6">
                  {cartItems.map((item, index) => (
                    <div key={index} className="flex justify-between text-sm">
                      <span className="text-gray-600">
                        {item.product_detail?.product?.name} × {item.quantity}
                      </span>
                      <span className="font-medium">
                        {formatCurrency((item.product_detail?.product?.selling_price || 0) * item.quantity)}
                      </span>
                    </div>
                  ))}
                </div>

                <div className="border-t border-gray-200 pt-4 space-y-3">
                  <div className="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span className="font-semibold">{formatCurrency(subtotal)}</span>
                  </div>
                  <div className="flex justify-between text-gray-600">
                    <span>Ongkos Kirim ({weight} kg)</span>
                    <span className="font-semibold">
                      {destinationCity ? formatCurrency(shippingCost) : '-'}
                    </span>
                  </div>
                </div>

                <div className="border-t border-gray-200 pt-4 mt-4">
                  <div className="flex justify-between text-lg font-bold text-gray-900">
                    <span>Total</span>
                    <span className="text-cyan-600">{formatCurrency(total)}</span>
                  </div>
                </div>

                <Button
                  type="submit"
                  variant="primary"
                  className="w-full mt-6"
                  size="lg"
                  loading={submitting}
                  disabled={
                    !destinationCity ||
                    !address ||
                    !paymentProof ||
                    (paymentMethod === 'transfer' && !selectedBank)
                  }
                >
                  <CheckCircleIcon className="h-5 w-5" />
                  Buat Pesanan
                </Button>

                <p className="text-xs text-gray-500 text-center mt-4">
                  Pesanan akan diproses setelah pembayaran terverifikasi oleh kasir
                </p>
              </div>
            </div>
          </div>
        </form>
      </div>

      {/* QRIS Modal */}
      <Modal
        isOpen={showQrisModal}
        onClose={() => setShowQrisModal(false)}
        title="Pembayaran QRIS"
        size="sm"
      >
        <div className="text-center">
          <p className="text-sm text-gray-600 mb-4">
            Silakan scan kode QR di bawah ini menggunakan aplikasi OVO, GoPay, Dana, atau Mobile Banking Anda.
          </p>
          <div className="bg-white p-4 rounded-2xl border-2 border-dashed border-gray-200 inline-block mb-4">
            <img
              src="/qris.jpeg"
              alt="QRIS Code"
              className="w-64 h-64 object-contain mx-auto"
            />
          </div>
          <div className="bg-cyan-50 p-3 rounded-lg">
            <p className="text-xs text-cyan-800 font-medium">
              Pastikan nama merchant adalah <span className="font-bold">DistroZone</span>
            </p>
          </div>
          <Button
            variant="primary"
            className="w-full mt-6"
            onClick={() => setShowQrisModal(false)}
          >
            Tutup
          </Button>
        </div>
      </Modal>
    </div>
  );
};

export default Checkout;