import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import {
  ShoppingCartIcon,
  HeartIcon,
  TruckIcon,
  ShieldCheckIcon,
  MinusIcon,
  PlusIcon,
  BoltIcon,
} from '@heroicons/react/24/outline';
import { HeartIcon as HeartSolidIcon } from '@heroicons/react/24/solid';
import apiClient from '../services/apiClient';
import Loading from '../components/Loading';
import Badge from '../components/Badge';
import Button from '../components/Button';
import { formatCurrency } from '../utils/formatters';
import { useUser } from '../contexts/UserContext';
import { useCart } from '../contexts/CartContext';
import { alert as swal } from '../utils/swal';

interface ProductDetail {
  id: number;
  color: { id: number; name: string };
  size: { id: number; name: string };
  stock: number;
  photos: Array<{ id: number; photo_url: string }>;
}

interface Product {
  id: number;
  name: string;
  selling_price: number;
  brand: { name: string };
  type: { name: string };
  product_details: ProductDetail[];
}

const ProductDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const { user } = useUser();
  const { refreshCart, triggerAnimation } = useCart();

  const [product, setProduct] = useState<Product | null>(null);
  const [loading, setLoading] = useState(true);
  const [selectedImage, setSelectedImage] = useState(0);
  const [selectedColor, setSelectedColor] = useState<number | null>(null);
  const [selectedSize, setSelectedSize] = useState<number | null>(null);
  const [quantity, setQuantity] = useState(1);
  const [isFavorite, setIsFavorite] = useState(false);
  const [addingToCart, setAddingToCart] = useState(false);
  const [addToCartSuccess, setAddToCartSuccess] = useState(false);
  const [buyingNow, setBuyingNow] = useState(false);

  useEffect(() => {
    fetchProduct();
  }, [id]);

  const fetchProduct = async () => {
    try {
      const response = await apiClient.get(`/products/${id}`);
      setProduct(response.data.data);

      // Auto-select first color if available
      const details = response.data.data?.product_details;
      if (Array.isArray(details) && details.length > 0) {
        const firstDetail = details[0];
        setSelectedColor(firstDetail.color?.id);
        setSelectedSize(firstDetail.size?.id);
      }
    } catch (error) {
      console.error('Failed to fetch product:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleAddToCart = async () => {
    if (!user) {
      navigate('/login');
      return;
    }

    if (!selectedColor || !selectedSize) {
      swal.warning('Pilihan Belum Lengkap', 'Pilih warna dan ukuran terlebih dahulu');
      return;
    }

    // Find the selected product detail
    const selectedDetail = product?.product_details?.find(
      (d) => d.color?.id === selectedColor && d.size?.id === selectedSize
    );

    if (!selectedDetail) {
      swal.error('Varian Tidak Tersedia', 'Varian yang dipilih tidak tersedia');
      return;
    }

    if (quantity > selectedDetail.stock) {
      swal.warning('Stok Terbatas', `Stok hanya tersedia ${selectedDetail.stock} pcs`);
      return;
    }

    setAddingToCart(true);
    try {
      await apiClient.post('/cart', {
        product_detail_id: selectedDetail.id,
        quantity: quantity,
      });

      // Refresh cart count and trigger animation
      await refreshCart();
      triggerAnimation();

      // Show success state on button
      setAddToCartSuccess(true);
      setTimeout(() => setAddToCartSuccess(false), 2000);
    } catch (error) {
      console.error('Failed to add to cart:', error);
      swal.error('Gagal!', 'Gagal menambahkan ke keranjang');
    } finally {
      setAddingToCart(false);
    }
  };

  const handleBuyNow = async () => {
    if (!user) {
      navigate('/login');
      return;
    }

    if (!selectedColor || !selectedSize) {
      swal.warning('Pilihan Belum Lengkap', 'Pilih warna dan ukuran terlebih dahulu');
      return;
    }

    // Find the selected product detail
    const selectedDetail = product?.product_details?.find(
      (d) => d.color?.id === selectedColor && d.size?.id === selectedSize
    );

    if (!selectedDetail) {
      swal.error('Varian Tidak Tersedia', 'Varian yang dipilih tidak tersedia');
      return;
    }

    if (quantity > selectedDetail.stock) {
      swal.warning('Stok Terbatas', `Stok hanya tersedia ${selectedDetail.stock} pcs`);
      return;
    }

    setBuyingNow(true);
    try {
      // Add to cart first
      await apiClient.post('/cart', {
        product_detail_id: selectedDetail.id,
        quantity: quantity,
      });

      // Refresh cart and navigate to checkout
      await refreshCart();

      // Navigate directly to checkout
      navigate('/checkout');
    } catch (error) {
      console.error('Failed to process buy now:', error);
      swal.error('Gagal!', 'Gagal memproses pembelian');
      setBuyingNow(false);
    }
  };

  if (loading) {
    return <Loading text="Memuat detail produk..." />;
  }

  if (!product) {
    return (
      <div className="container-custom py-16 text-center">
        <h2 className="text-2xl font-bold text-gray-900 mb-4">Produk tidak ditemukan</h2>
        <Button onClick={() => navigate('/products')}>Kembali ke Produk</Button>
      </div>
    );
  }

  const productDetails = Array.isArray(product.product_details) ? product.product_details : [];

  const availableColors = [...new Map(productDetails.map(d => [d.color?.id, d.color])).values()].filter(c => c);
  const availableSizes = selectedColor
    ? [...new Map(productDetails
      .filter(d => d.color?.id === selectedColor)
      .map(d => [d.size?.id, d.size])).values()].filter(s => s)
    : [];

  const selectedDetail = productDetails.find(
    d => d.color?.id === selectedColor && d.size?.id === selectedSize
  );

  const currentStock = selectedDetail?.stock || 0;
  const allImages = selectedDetail?.photos || [];

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="container-custom">
        {/* Breadcrumb */}
        <nav className="mb-6 text-sm">
          <ol className="flex items-center space-x-2 text-gray-600">
            <li>
              <a href="/" className="hover:text-cyan-600">Home</a>
            </li>
            <li>/</li>
            <li>
              <a href="/products" className="hover:text-cyan-600">Produk</a>
            </li>
            <li>/</li>
            <li className="text-gray-900 font-medium">{product.name}</li>
          </ol>
        </nav>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
          {/* Images Section */}
          <div className="space-y-4">
            {/* Main Image */}
            <div className="image-zoom-container bg-white rounded-2xl overflow-hidden shadow-lg">
              <img
                src={allImages[selectedImage]?.photo_url || '/placeholder-tshirt.jpg'}
                alt={product.name}
                className="w-full h-[500px] object-cover"
              />
            </div>

            {/* Thumbnails */}
            {allImages.length > 1 && (
              <div className="grid grid-cols-5 gap-3">
                {allImages.map((photo, index) => (
                  <button
                    key={photo.id}
                    onClick={() => setSelectedImage(index)}
                    className={`rounded-lg overflow-hidden border-2 transition-all ${selectedImage === index
                      ? 'border-cyan-500 shadow-md'
                      : 'border-gray-200 hover:border-gray-300'
                      }`}
                  >
                    <img
                      src={photo.photo_url}
                      alt={`${product.name} ${index + 1}`}
                      className="w-full h-20 object-cover"
                    />
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Product Info Section */}
          <div className="space-y-6">
            {/* Title & Price */}
            <div>
              <div className="flex items-start justify-between mb-2">
                <div className="flex-1">
                  <p className="text-sm text-gray-600 mb-1">
                    {product.brand.name} • {product.type.name}
                  </p>
                  <h1 className="text-3xl font-heading font-bold text-gray-900">
                    {product.name}
                  </h1>
                </div>
                <button
                  onClick={() => setIsFavorite(!isFavorite)}
                  className="p-3 rounded-full hover:bg-gray-100 transition-colors"
                >
                  {isFavorite ? (
                    <HeartSolidIcon className="h-6 w-6 text-red-500" />
                  ) : (
                    <HeartIcon className="h-6 w-6 text-gray-400" />
                  )}
                </button>
              </div>

              <div className="flex items-baseline gap-3">
                <p className="text-4xl font-bold text-cyan-600">
                  {formatCurrency(product.selling_price)}
                </p>
                {currentStock > 0 ? (
                  <Badge variant="success">Tersedia</Badge>
                ) : (
                  <Badge variant="error">Stok Habis</Badge>
                )}
              </div>
            </div>

            {/* Color Selection */}
            <div>
              <label className="block text-sm font-medium text-gray-900 mb-3">
                Pilih Warna
              </label>
              <div className="flex flex-wrap gap-2">
                {availableColors.map((color) => (
                  <button
                    key={color.id}
                    onClick={() => {
                      setSelectedColor(color.id);
                      // Find first available size for this color
                      const firstAvailableSize = productDetails.find(
                        d => d.color?.id === color.id && d.stock > 0
                      );
                      if (firstAvailableSize) {
                        setSelectedSize(firstAvailableSize.size?.id);
                      } else {
                        // Fallback: select first size even if out of stock
                        const firstSize = productDetails.find(d => d.color?.id === color.id);
                        if (firstSize) setSelectedSize(firstSize.size?.id);
                      }
                      setSelectedImage(0); // Reset image when color changes
                    }}
                    className={`px-4 py-2 rounded-lg border-2 transition-all ${selectedColor === color.id
                      ? 'border-cyan-500 bg-cyan-50 text-cyan-700 font-semibold'
                      : 'border-gray-200 hover:border-gray-300 text-gray-700'
                      }`}
                  >
                    {color.name}
                  </button>
                ))}
              </div>
            </div>

            {/* Size Selection */}
            <div>
              <label className="block text-sm font-medium text-gray-900 mb-3">
                Pilih Ukuran
              </label>
              <div className="flex flex-wrap gap-2">
                {availableSizes.map((size) => {
                  const sizeDetail = productDetails.find(
                    d => d.color?.id === selectedColor && d.size?.id === size?.id
                  );
                  const isAvailable = (sizeDetail?.stock || 0) > 0;

                  return (
                    <button
                      key={size.id}
                      onClick={() => isAvailable && setSelectedSize(size.id)}
                      disabled={!isAvailable}
                      className={`px-4 py-2 rounded-lg border-2 transition-all ${selectedSize === size.id
                        ? 'border-cyan-500 bg-cyan-50 text-cyan-700 font-semibold'
                        : isAvailable
                          ? 'border-gray-200 hover:border-gray-300 text-gray-700'
                          : 'border-gray-100 bg-gray-50 text-gray-400 cursor-not-allowed line-through'
                        }`}
                    >
                      {size.name}
                    </button>
                  );
                })}
              </div>
            </div>

            {/* Quantity */}
            <div>
              <label className="block text-sm font-medium text-gray-900 mb-3">
                Jumlah
              </label>
              <div className="flex items-center gap-4">
                <div className="flex items-center border-2 border-gray-200 rounded-lg">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="p-3 hover:bg-gray-50 transition-colors"
                    disabled={quantity <= 1}
                  >
                    <MinusIcon className="h-5 w-5 text-gray-600" />
                  </button>
                  <span className="px-6 font-semibold text-lg">{quantity}</span>
                  <button
                    onClick={() => setQuantity(Math.min(currentStock, quantity + 1))}
                    className="p-3 hover:bg-gray-50 transition-colors"
                    disabled={quantity >= currentStock}
                  >
                    <PlusIcon className="h-5 w-5 text-gray-600" />
                  </button>
                </div>
                <p className="text-sm text-gray-600">
                  Stok tersedia: <span className="font-semibold">{currentStock}</span>
                </p>
              </div>
            </div>

            {/* Actions */}
            <div className="flex gap-3 pt-4">
              <Button
                onClick={handleAddToCart}
                variant={addToCartSuccess ? 'secondary' : 'outline'}
                size="lg"
                className={`flex-1 transition-all duration-300 ${addToCartSuccess ? 'bg-green-500 hover:bg-green-600 text-white border-green-500 animate-add-to-cart-success' : ''}`}
                loading={addingToCart}
                disabled={!selectedColor || !selectedSize || currentStock === 0 || addToCartSuccess}
              >
                {addToCartSuccess ? (
                  <>
                    <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                    </svg>
                    Berhasil Ditambahkan!
                  </>
                ) : (
                  <>
                    <ShoppingCartIcon className="h-5 w-5" />
                    Tambah ke Keranjang
                  </>
                )}
              </Button>
              <Button
                onClick={handleBuyNow}
                variant="primary"
                size="lg"
                className="flex-1"
                loading={buyingNow}
                disabled={!selectedColor || !selectedSize || currentStock === 0 || buyingNow}
              >
                <BoltIcon className="h-5 w-5" />
                Beli Sekarang
              </Button>
            </div>

            {/* Ask CS Button */}
            <div className="pt-2">
              <Button
                variant="secondary"
                size="lg"
                className="w-full bg-white hover:bg-gray-50 text-gray-700 border-0 shadow-md hover:shadow-lg"
                onClick={() => navigate('/chat')}
              >
                Tanya CS
              </Button>
            </div>

            {/* Info Cards */}
            <div className="grid grid-cols-2 gap-4 pt-6">
              <div className="flex items-center gap-3 p-4 bg-gray-100 rounded-lg">
                <TruckIcon className="h-6 w-6 text-cyan-600" />
                <div>
                  <p className="text-sm font-semibold text-gray-900">Pengiriman</p>
                  <p className="text-xs text-gray-600">Ke seluruh Jawa</p>
                </div>
              </div>
              <div className="flex items-center gap-3 p-4 bg-gray-100 rounded-lg">
                <ShieldCheckIcon className="h-6 w-6 text-cyan-600" />
                <div>
                  <p className="text-sm font-semibold text-gray-900">Original</p>
                  <p className="text-xs text-gray-600">100% Authentic</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetailPage;