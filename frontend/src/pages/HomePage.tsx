import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import {
  TruckIcon,
  ShieldCheckIcon,
  ClockIcon,
  ChatBubbleLeftRightIcon,
  SparklesIcon,
  ArrowRightIcon,
} from '@heroicons/react/24/outline';
import apiClient from '../services/apiClient';
import ProductCard from '../components/ProductCard';
import { ProductGridLoading } from '../components/Loading';

interface Product {
  id: number;
  name: string;
  brand: { name: string };
  type: { name: string };
  selling_price: number;
  product_details: Array<{
    id: number;
    stock: number;
    photos: Array<{ photo_url: string }>;
  }>;
}

const HomePage: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchFeaturedProducts = async () => {
      try {
        const response = await apiClient.get('/products?limit=8');
        const allProducts = response.data.data?.data || response.data.data || [];
        setProducts(Array.isArray(allProducts) ? allProducts.slice(0, 8) : []); // Get first 8 products
      } catch (error) {
        console.error('Failed to fetch products:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchFeaturedProducts();
  }, []);

  const getTotalStock = (product: any) => {
    const details = product.product_details || product.productDetails;
    if (!Array.isArray(details)) return 0;
    return details.reduce((sum: number, detail: any) => sum + (detail?.stock || 0), 0);
  };

  const getProductImage = (product: any) => {
    const details = product.product_details || product.productDetails;
    if (!Array.isArray(details) || details.length === 0) {
      return '/placeholder-tshirt.jpg';
    }
    const firstDetail = details[0];
    const photos = firstDetail?.photos || firstDetail?.Photos;
    if (photos && Array.isArray(photos) && photos.length > 0) {
      return photos[0].photo_url;
    }
    return '/placeholder-tshirt.jpg';
  };

  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <section className="relative h-[600px] bg-gradient-to-br from-cyan-500 via-cyan-600 to-blue-600 overflow-hidden">
        <div className="absolute inset-0 bg-[url('C:/Users/Admin/.gemini/antigravity/brain/6911f73d-9bde-4533-86f0-a1c856fac4c7/hero_distro_banner_1768712768694.png')] bg-cover bg-center opacity-20"></div>
        <div className="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>

        <div className="relative container-custom h-full flex items-center">
          <div className="max-w-2xl space-y-6 animate-fade-in-up">
            <div className="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-md rounded-full text-white">
              <SparklesIcon className="h-5 w-5" />
              <span className="text-sm font-medium">Koleksi Terbaru 2026</span>
            </div>

            <h1 className="text-5xl md:text-7xl font-heading font-bold text-white leading-tight">
              Koleksi Kaos
              <br />
              <span className="gradient-text bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                Distro Terkini
              </span>
            </h1>

            <p className="text-xl text-white/90 leading-relaxed">
              Temukan gaya unikmu dengan koleksi kaos distro berkualitas.
              Berbagai brand, model, warna dan ukuran tersedia.
            </p>

            <div className="flex flex-wrap gap-4">
              <Link to="/products" className="btn btn-primary btn-lg shadow-2xl">
                Belanja Sekarang
                <ArrowRightIcon className="h-5 w-5" />
              </Link>
              <Link to="/chat" className="btn glass text-white border-white/30 hover:bg-white/20">
                <ChatBubbleLeftRightIcon className="h-5 w-5" />
                Hubungi CS
              </Link>
            </div>
          </div>
        </div>

        {/* Decorative Elements */}
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-50 to-transparent"></div>
      </section>

      {/* Features Section */}
      <section className="py-16 bg-gray-50">
        <div className="container-custom">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div className="flex items-start gap-4 p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
              <div className="p-3 bg-cyan-100 rounded-lg">
                <TruckIcon className="h-6 w-6 text-cyan-600" />
              </div>
              <div>
                <h3 className="font-heading font-semibold text-gray-900 mb-1">
                  Pengiriman Cepat
                </h3>
                <p className="text-sm text-gray-600">
                  Kirim ke seluruh Pulau Jawa
                </p>
              </div>
            </div>

            <div className="flex items-start gap-4 p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
              <div className="p-3 bg-cyan-100 rounded-lg">
                <ShieldCheckIcon className="h-6 w-6 text-cyan-600" />
              </div>
              <div>
                <h3 className="font-heading font-semibold text-gray-900 mb-1">
                  Kualitas Terjamin
                </h3>
                <p className="text-sm text-gray-600">
                  100% produk original
                </p>
              </div>
            </div>

            <div className="flex items-start gap-4 p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
              <div className="p-3 bg-cyan-100 rounded-lg">
                <ClockIcon className="h-6 w-6 text-cyan-600" />
              </div>
              <div>
                <h3 className="font-heading font-semibold text-gray-900 mb-1">
                  Buka Setiap Hari
                </h3>
                <p className="text-sm text-gray-600">
                  10:00 - 17:00 WIB
                </p>
              </div>
            </div>

            <div className="flex items-start gap-4 p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
              <div className="p-3 bg-cyan-100 rounded-lg">
                <ChatBubbleLeftRightIcon className="h-6 w-6 text-cyan-600" />
              </div>
              <div>
                <h3 className="font-heading font-semibold text-gray-900 mb-1">
                  Customer Service
                </h3>
                <p className="text-sm text-gray-600">
                  Siap melayani Anda
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Featured Products Section */}
      <section className="py-20">
        <div className="container-custom">
          <div className="text-center mb-12">
            <h2 className="text-4xl font-heading font-bold text-gray-900 mb-4">
              Produk <span className="gradient-text">Terbaru</span>
            </h2>
            <p className="text-lg text-gray-600 max-w-2xl mx-auto">
              Lihat koleksi kaos distro terbaru kami dengan berbagai pilihan warna, ukuran dan model
            </p>
          </div>

          {loading ? (
            <ProductGridLoading count={8} columns={4} />
          ) : products.length > 0 ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              {products.map((product) => (
                <ProductCard
                  key={product.id}
                  id={product.id}
                  name={product.name}
                  brand={product.brand.name}
                  type={product.type.name}
                  price={product.selling_price}
                  image={getProductImage(product)}
                  stock={getTotalStock(product)}
                />
              ))}
            </div>
          ) : (
            <div className="text-center py-12">
              <p className="text-gray-500">Belum ada produk tersedia</p>
            </div>
          )}

          <div className="text-center mt-12">
            <Link to="/products" className="btn btn-outline btn-lg">
              Lihat Semua Produk
              <ArrowRightIcon className="h-5 w-5" />
            </Link>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-gradient-to-br from-cyan-500 to-blue-600 relative overflow-hidden">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute inset-0" style={{
            backgroundImage: 'radial-gradient(circle at 2px 2px, white 1px, transparent 0)',
            backgroundSize: '40px 40px'
          }}></div>
        </div>

        <div className="container-custom relative">
          <div className="max-w-3xl mx-auto text-center space-y-6">
            <h2 className="text-4xl md:text-5xl font-heading font-bold text-white">
              Siap Untuk Berbelanja?
            </h2>
            <p className="text-xl text-white/90">
              Daftar sekarang dan dapatkan pengalaman berbelanja terbaik di DistroZone
            </p>
            <div className="flex flex-wrap justify-center gap-4 pt-4">
              <Link to="/register" className="btn bg-white text-cyan-600 hover:bg-gray-100 shadow-xl">
                Daftar Sekarang
              </Link>
              <Link to="/products" className="btn glass text-white border-white/30 hover:bg-white/20">
                Mulai Belanja
              </Link>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default HomePage;