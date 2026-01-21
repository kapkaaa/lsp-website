import React, { useState, useEffect } from 'react';
import { useSearchParams } from 'react-router-dom';
import {
  FunnelIcon,
  XMarkIcon,
  AdjustmentsHorizontalIcon,
} from '@heroicons/react/24/outline';
import apiClient from '../services/apiClient';
import ProductCard from '../components/ProductCard';
import { ProductGridLoading } from '../components/Loading';
import { formatCurrency } from '../utils/formatters';

interface Product {
  id: number;
  name: string;
  brand: { id: number; name: string };
  type: { id: number; name: string };
  selling_price: number;
  product_details: Array<{
    id: number;
    stock: number;
    color: { id: number; name: string };
    size: { id: number; name: string };
    photos: Array<{ photo_url: string }>;
  }>;
}

interface FilterOptions {
  brands: Array<{ id: number; name: string }>;
  types: Array<{ id: number; name: string }>;
  colors: Array<{ id: number; name: string }>;
  sizes: Array<{ id: number; name: string }>;
}

const ProductList: React.FC = () => {
  const [searchParams, setSearchParams] = useSearchParams();
  const [products, setProducts] = useState<Product[]>([]);
  const [filterOptions, setFilterOptions] = useState<FilterOptions>({
    brands: [],
    types: [],
    colors: [],
    sizes: [],
  });
  const [loading, setLoading] = useState(true);
  const [showFilters, setShowFilters] = useState(false);

  // Filter states
  const [searchQuery, setSearchQuery] = useState(searchParams.get('search') || '');
  const [selectedBrand, setSelectedBrand] = useState(searchParams.get('brand') || '');
  const [selectedType, setSelectedType] = useState(searchParams.get('type') || '');
  const [selectedColor, setSelectedColor] = useState(searchParams.get('color') || '');
  const [selectedSize, setSelectedSize] = useState(searchParams.get('size') || '');
  const [priceMin, setPriceMin] = useState(searchParams.get('priceMin') || '');
  const [priceMax, setPriceMax] = useState(searchParams.get('priceMax') || '');
  const [sortBy, setSortBy] = useState(searchParams.get('sort') || 'newest');

  useEffect(() => {
    setSearchQuery(searchParams.get('search') || '');
    setSelectedBrand(searchParams.get('brand') || '');
    setSelectedType(searchParams.get('type') || '');
    setSelectedColor(searchParams.get('color') || '');
    setSelectedSize(searchParams.get('size') || '');
    setPriceMin(searchParams.get('priceMin') || '');
    setPriceMax(searchParams.get('priceMax') || '');
    setSortBy(searchParams.get('sort') || 'newest');
  }, [searchParams]);

  useEffect(() => {
    const timer = setTimeout(() => {
      fetchProducts();
    }, 400); // Debounce delay

    return () => clearTimeout(timer);
  }, [searchQuery, selectedBrand, selectedType, selectedColor, selectedSize, priceMin, priceMax, sortBy]);

  useEffect(() => {
    fetchFilterOptions();
  }, []);

  const fetchProducts = async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (searchQuery) params.append('search', searchQuery);
      if (selectedBrand) params.append('brand_id', selectedBrand);
      if (selectedType) params.append('type_id', selectedType);

      const response = await apiClient.get(`/products?${params.toString()}`);
      let fetchedProducts = response.data.data.data || response.data.data || [];

      // Client-side filtering for color, size, and price (if backend doesn't support)
      fetchedProducts = fetchedProducts.filter((product: Product) => {
        // Color filter
        if (selectedColor) {
          const hasColor = product.product_details.some(
            (detail) => detail.color.id.toString() === selectedColor
          );
          if (!hasColor) return false;
        }

        // Size filter
        if (selectedSize) {
          const hasSize = product.product_details.some(
            (detail) => detail.size.id.toString() === selectedSize
          );
          if (!hasSize) return false;
        }

        // Price filter
        if (priceMin && product.selling_price < parseInt(priceMin)) return false;
        if (priceMax && product.selling_price > parseInt(priceMax)) return false;

        return true;
      });

      // Client-side sorting
      fetchedProducts.sort((a: Product, b: Product) => {
        switch (sortBy) {
          case 'price-asc':
            return a.selling_price - b.selling_price;
          case 'price-desc':
            return b.selling_price - a.selling_price;
          case 'name-asc':
            return a.name.localeCompare(b.name);
          case 'name-desc':
            return b.name.localeCompare(a.name);
          case 'newest':
          default:
            return b.id - a.id;
        }
      });

      setProducts(fetchedProducts);
    } catch (error) {
      console.error('Failed to fetch products:', error);
    } finally {
      setLoading(false);
    }
  };

  const fetchFilterOptions = async () => {
    try {
      // Fetch all products to extract unique filter options
      const response = await apiClient.get('/products');
      const allProducts = response.data.data?.data || response.data.data || [];

      const brands = new Map();
      const types = new Map();
      const colors = new Map();
      const sizes = new Map();

      if (Array.isArray(allProducts)) {
        allProducts.forEach((product: Product) => {
          if (product.brand) {
            brands.set(product.brand.id, product.brand);
          }
          if (product.type) {
            types.set(product.type.id, product.type);
          }

          if (Array.isArray(product.product_details)) {
            product.product_details.forEach((detail) => {
              if (detail.color) {
                colors.set(detail.color.id, detail.color);
              }
              if (detail.size) {
                sizes.set(detail.size.id, detail.size);
              }
            });
          }
        });
      }

      setFilterOptions({
        brands: Array.from(brands.values()),
        types: Array.from(types.values()),
        colors: Array.from(colors.values()),
        sizes: Array.from(sizes.values()),
      });
    } catch (error) {
      console.error('Failed to fetch filter options:', error);
    }
  };

  const clearFilters = () => {
    setSearchQuery('');
    setSelectedBrand('');
    setSelectedType('');
    setSelectedColor('');
    setSelectedSize('');
    setPriceMin('');
    setPriceMax('');
    setSortBy('newest');
    setSearchParams({});
  };

  const hasActiveFilters = selectedBrand || selectedType || selectedColor || selectedSize || priceMin || priceMax;

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
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="container-custom">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-heading font-bold text-gray-900 mb-2">
            Semua Produk
          </h1>
          <p className="text-gray-600">
            Temukan kaos distro favoritmu dari berbagai pilihan
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          {/* Sidebar Filters - Desktop */}
          <aside className={`lg:block ${showFilters ? 'block' : 'hidden'} lg:col-span-1`}>
            <div className="sticky top-24">
              <div className="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div className="flex items-center justify-between">
                  <h2 className="text-lg font-heading font-semibold text-gray-900 flex items-center gap-2">
                    <FunnelIcon className="h-5 w-5 text-cyan-600" />
                    Filter
                  </h2>
                  {hasActiveFilters && (
                    <button
                      onClick={clearFilters}
                      className="text-sm text-cyan-600 hover:text-cyan-700 font-medium"
                    >
                      Reset
                    </button>
                  )}
                </div>

                {/* Search */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Cari Produk
                  </label>
                  <input
                    type="text"
                    value={searchQuery}
                    onChange={(e) => {
                      const value = e.target.value;
                      setSearchQuery(value);
                      const params = new URLSearchParams(searchParams);
                      if (value) params.set('search', value);
                      else params.delete('search');
                      setSearchParams(params, { replace: true });
                    }}
                    placeholder="Nama produk..."
                    className="input"
                  />
                </div>

                {/* Brand Filter */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Brand
                  </label>
                  <select
                    value={selectedBrand}
                    onChange={(e) => setSelectedBrand(e.target.value)}
                    className="input"
                  >
                    <option value="">Semua Brand</option>
                    {filterOptions.brands.map((brand) => (
                      <option key={brand.id} value={brand.id}>
                        {brand.name}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Type Filter */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Tipe
                  </label>
                  <select
                    value={selectedType}
                    onChange={(e) => setSelectedType(e.target.value)}
                    className="input"
                  >
                    <option value="">Semua Tipe</option>
                    {filterOptions.types.map((type) => (
                      <option key={type.id} value={type.id}>
                        {type.name}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Color Filter */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Warna
                  </label>
                  <select
                    value={selectedColor}
                    onChange={(e) => setSelectedColor(e.target.value)}
                    className="input"
                  >
                    <option value="">Semua Warna</option>
                    {filterOptions.colors.map((color) => (
                      <option key={color.id} value={color.id}>
                        {color.name}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Size Filter */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Ukuran
                  </label>
                  <select
                    value={selectedSize}
                    onChange={(e) => setSelectedSize(e.target.value)}
                    className="input"
                  >
                    <option value="">Semua Ukuran</option>
                    {filterOptions.sizes.map((size) => (
                      <option key={size.id} value={size.id}>
                        {size.name}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Price Range */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Rentang Harga
                  </label>
                  <div className="grid grid-cols-2 gap-2">
                    <input
                      type="number"
                      value={priceMin}
                      onChange={(e) => setPriceMin(e.target.value)}
                      placeholder="Min"
                      className="input"
                    />
                    <input
                      type="number"
                      value={priceMax}
                      onChange={(e) => setPriceMax(e.target.value)}
                      placeholder="Max"
                      className="input"
                    />
                  </div>
                </div>
              </div>
            </div>
          </aside>

          {/* Main Content */}
          <main className="lg:col-span-3">
            {/* Toolbar */}
            <div className="bg-white rounded-xl shadow-sm p-4 mb-6">
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-4">
                  <button
                    onClick={() => setShowFilters(!showFilters)}
                    className="lg:hidden btn btn-secondary"
                  >
                    <AdjustmentsHorizontalIcon className="h-5 w-5" />
                    Filter
                  </button>
                  <p className="text-sm text-gray-600">
                    <span className="font-semibold text-gray-900">{products.length}</span> produk ditemukan
                  </p>
                </div>

                {/* Sort */}
                <div className="flex items-center gap-2">
                  <label className="text-sm text-gray-600 hidden sm:block">
                    Urutkan:
                  </label>
                  <select
                    value={sortBy}
                    onChange={(e) => setSortBy(e.target.value)}
                    className="input py-2 text-sm"
                  >
                    <option value="newest">Terbaru</option>
                    <option value="price-asc">Harga: Rendah - Tinggi</option>
                    <option value="price-desc">Harga: Tinggi - Rendah</option>
                    <option value="name-asc">Nama: A - Z</option>
                    <option value="name-desc">Nama: Z - A</option>
                  </select>
                </div>
              </div>

              {/* Active Filters */}
              {hasActiveFilters && (
                <div className="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-200">
                  {selectedBrand && (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm">
                      Brand: {filterOptions.brands.find(b => b.id.toString() === selectedBrand)?.name}
                      <button onClick={() => setSelectedBrand('')}>
                        <XMarkIcon className="h-4 w-4" />
                      </button>
                    </span>
                  )}
                  {selectedType && (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm">
                      Tipe: {filterOptions.types.find(t => t.id.toString() === selectedType)?.name}
                      <button onClick={() => setSelectedType('')}>
                        <XMarkIcon className="h-4 w-4" />
                      </button>
                    </span>
                  )}
                  {selectedColor && (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm">
                      Warna: {filterOptions.colors.find(c => c.id.toString() === selectedColor)?.name}
                      <button onClick={() => setSelectedColor('')}>
                        <XMarkIcon className="h-4 w-4" />
                      </button>
                    </span>
                  )}
                  {selectedSize && (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm">
                      Ukuran: {filterOptions.sizes.find(s => s.id.toString() === selectedSize)?.name}
                      <button onClick={() => setSelectedSize('')}>
                        <XMarkIcon className="h-4 w-4" />
                      </button>
                    </span>
                  )}
                  {(priceMin || priceMax) && (
                    <span className="inline-flex items-center gap-1 px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm">
                      Harga: {priceMin ? formatCurrency(parseInt(priceMin)) : '0'} - {priceMax ? formatCurrency(parseInt(priceMax)) : '∞'}
                      <button onClick={() => { setPriceMin(''); setPriceMax(''); }}>
                        <XMarkIcon className="h-4 w-4" />
                      </button>
                    </span>
                  )}
                </div>
              )}
            </div>

            {/* Products Grid */}
            {loading ? (
              <ProductGridLoading count={9} columns={3} />
            ) : products.length > 0 ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
              <div className="text-center py-16 bg-white rounded-xl">
                <div className="max-w-md mx-auto">
                  <div className="text-6xl mb-4">🔍</div>
                  <h3 className="text-xl font-semibold text-gray-900 mb-2">
                    Produk tidak ditemukan
                  </h3>
                  <p className="text-gray-600 mb-6">
                    Coba ubah filter atau kata kunci pencarian Anda
                  </p>
                  <button onClick={clearFilters} className="btn btn-primary">
                    Reset Filter
                  </button>
                </div>
              </div>
            )}
          </main>
        </div>
      </div>
    </div>
  );
};

export default ProductList;