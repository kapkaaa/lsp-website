import React, { useState, useEffect } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import {
  ShoppingCartIcon,
  MagnifyingGlassIcon,
  UserIcon,
  Bars3Icon,
  XMarkIcon,
  ChatBubbleLeftRightIcon,
  ClipboardDocumentListIcon,
} from '@heroicons/react/24/outline';
import { useUser } from '../contexts/UserContext';
import Badge from './Badge';

const Navbar: React.FC = () => {
  const { user, logout } = useUser();
  const location = useLocation();
  const navigate = useNavigate();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [showUserMenu, setShowUserMenu] = useState(false);

  const isActive = (path: string) => location.pathname === path;

  // Handle scroll effect
  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 20);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/products?search=${encodeURIComponent(searchQuery)}`);
      setSearchQuery('');
    }
  };

  return (
    <nav
      className={`sticky top-0 z-40 w-full transition-all duration-300 ${scrolled ? 'glass shadow-lg' : 'bg-white shadow-md'
        }`}
    >
      <div className="container-custom">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <Link
            to="/"
            className="flex items-center space-x-2 group"
          >
            <div className="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-lg flex items-center justify-center shadow-md group-hover:shadow-glow transition-all">
              <span className="text-white font-bold text-xl">DZ</span>
            </div>
            <span className="text-2xl font-heading font-bold gradient-text">
              DistroZone
            </span>
          </Link>

          {/* Search Bar - Desktop */}
          <div className="hidden md:flex flex-1 max-w-lg mx-8">
            <form onSubmit={handleSearch} className="w-full relative">
              <input
                type="text"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Cari kaos distro..."
                className="w-full pl-12 pr-4 py-3 rounded-full border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 outline-none transition-all"
              />
              <MagnifyingGlassIcon className="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
            </form>
          </div>

          {/* Navigation Links - Desktop */}
          <div className="hidden lg:flex items-center space-x-1">
            <Link
              to="/products"
              className={`px-4 py-2 rounded-lg font-medium transition-colors ${isActive('/products')
                  ? 'text-cyan-600 bg-cyan-50'
                  : 'text-gray-700 hover:text-cyan-600 hover:bg-gray-50'
                }`}
            >
              Produk
            </Link>

            {user && (
              <>
                <Link
                  to="/cart"
                  className={`px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 relative ${isActive('/cart')
                      ? 'text-cyan-600 bg-cyan-50'
                      : 'text-gray-700 hover:text-cyan-600 hover:bg-gray-50'
                    }`}
                >
                  <ShoppingCartIcon className="h-5 w-5" />
                  <span>Keranjang</span>
                  {/* Cart badge - will be dynamic later */}
                  <Badge variant="error" className="absolute -top-1 -right-1 px-2 py-0.5 text-xs">
                    0
                  </Badge>
                </Link>

                <Link
                  to="/orders"
                  className={`px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 ${isActive('/orders')
                      ? 'text-cyan-600 bg-cyan-50'
                      : 'text-gray-700 hover:text-cyan-600 hover:bg-gray-50'
                    }`}
                >
                  <ClipboardDocumentListIcon className="h-5 w-5" />
                  <span>Pesanan</span>
                </Link>

                <Link
                  to="/chat"
                  className={`px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 ${isActive('/chat')
                      ? 'text-cyan-600 bg-cyan-50'
                      : 'text-gray-700 hover:text-cyan-600 hover:bg-gray-50'
                    }`}
                >
                  <ChatBubbleLeftRightIcon className="h-5 w-5" />
                  <span>CS</span>
                </Link>
              </>
            )}
          </div>

          {/* User Menu / Auth - Desktop */}
          <div className="hidden lg:flex items-center space-x-3">
            {user ? (
              <div className="relative">
                <button
                  onClick={() => setShowUserMenu(!showUserMenu)}
                  className="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors"
                >
                  <div className="w-8 h-8 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-full flex items-center justify-center">
                    <UserIcon className="h-5 w-5 text-white" />
                  </div>
                  <span className="font-medium text-gray-700">{user.name}</span>
                </button>

                {showUserMenu && (
                  <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 animate-scale-in">
                    <div className="px-4 py-2 border-b border-gray-100">
                      <p className="text-sm font-medium text-gray-900">{user.name}</p>
                      <p className="text-xs text-gray-500">{user.email}</p>
                    </div>
                    <button
                      onClick={() => {
                        logout();
                        setShowUserMenu(false);
                      }}
                      className="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                    >
                      Logout
                    </button>
                  </div>
                )}
              </div>
            ) : (
              <div className="flex items-center gap-2">
                <Link to="/login" className="btn btn-ghost">
                  Masuk
                </Link>
                <Link to="/register" className="btn btn-primary">
                  Daftar
                </Link>
              </div>
            )}
          </div>

          {/* Mobile Menu Button */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
          >
            {mobileMenuOpen ? (
              <XMarkIcon className="h-6 w-6 text-gray-700" />
            ) : (
              <Bars3Icon className="h-6 w-6 text-gray-700" />
            )}
          </button>
        </div>
      </div>

      {/* Mobile Menu */}
      {mobileMenuOpen && (
        <div className="lg:hidden border-t border-gray-200 bg-white animate-fade-in">
          <div className="container-custom py-4 space-y-4">
            {/* Search - Mobile */}
            <form onSubmit={handleSearch} className="relative">
              <input
                type="text"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Cari kaos distro..."
                className="w-full pl-12 pr-4 py-3 rounded-full border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 outline-none"
              />
              <MagnifyingGlassIcon className="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
            </form>

            {/* Links - Mobile */}
            <div className="space-y-1">
              <Link
                to="/"
                onClick={() => setMobileMenuOpen(false)}
                className={`block px-4 py-3 rounded-lg font-medium transition-colors ${isActive('/')
                    ? 'text-cyan-600 bg-cyan-50'
                    : 'text-gray-700 hover:bg-gray-50'
                  }`}
              >
                Home
              </Link>
              <Link
                to="/products"
                onClick={() => setMobileMenuOpen(false)}
                className={`block px-4 py-3 rounded-lg font-medium transition-colors ${isActive('/products')
                    ? 'text-cyan-600 bg-cyan-50'
                    : 'text-gray-700 hover:bg-gray-50'
                  }`}
              >
                Produk
              </Link>

              {user && (
                <>
                  <Link
                    to="/cart"
                    onClick={() => setMobileMenuOpen(false)}
                    className={`flex items-center gap-2 px-4 py-3 rounded-lg font-medium transition-colors ${isActive('/cart')
                        ? 'text-cyan-600 bg-cyan-50'
                        : 'text-gray-700 hover:bg-gray-50'
                      }`}
                  >
                    <ShoppingCartIcon className="h-5 w-5" />
                    <span>Keranjang</span>
                  </Link>
                  <Link
                    to="/orders"
                    onClick={() => setMobileMenuOpen(false)}
                    className={`flex items-center gap-2 px-4 py-3 rounded-lg font-medium transition-colors ${isActive('/orders')
                        ? 'text-cyan-600 bg-cyan-50'
                        : 'text-gray-700 hover:bg-gray-50'
                      }`}
                  >
                    <ClipboardDocumentListIcon className="h-5 w-5" />
                    <span>Pesanan</span>
                  </Link>
                  <Link
                    to="/chat"
                    onClick={() => setMobileMenuOpen(false)}
                    className={`flex items-center gap-2 px-4 py-3 rounded-lg font-medium transition-colors ${isActive('/chat')
                        ? 'text-cyan-600 bg-cyan-50'
                        : 'text-gray-700 hover:bg-gray-50'
                      }`}
                  >
                    <ChatBubbleLeftRightIcon className="h-5 w-5" />
                    <span>Customer Service</span>
                  </Link>
                </>
              )}
            </div>

            {/* Auth - Mobile */}
            {user ? (
              <div className="pt-4 border-t border-gray-200">
                <div className="px-4 py-2">
                  <p className="font-medium text-gray-900">{user.name}</p>
                  <p className="text-sm text-gray-500">{user.email}</p>
                </div>
                <button
                  onClick={() => {
                    logout();
                    setMobileMenuOpen(false);
                  }}
                  className="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                >
                  Logout
                </button>
              </div>
            ) : (
              <div className="flex flex-col gap-2 pt-4 border-t border-gray-200">
                <Link
                  to="/login"
                  onClick={() => setMobileMenuOpen(false)}
                  className="btn btn-outline w-full"
                >
                  Masuk
                </Link>
                <Link
                  to="/register"
                  onClick={() => setMobileMenuOpen(false)}
                  className="btn btn-primary w-full"
                >
                  Daftar
                </Link>
              </div>
            )}
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;