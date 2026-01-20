import React from 'react';
import { Link } from 'react-router-dom';
import {
  MapPinIcon,
  PhoneIcon,
  EnvelopeIcon,
  ClockIcon,
} from '@heroicons/react/24/outline';

const Footer: React.FC = () => {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
      <div className="container-custom py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
          {/* Brand & Description */}
          <div className="space-y-4">
            <div className="flex items-center space-x-2">
              <div className="w-10 h-10 bg-transparent flex items-center justify-center">
                <img src="/logo.png" alt="DistroZone Logo" className="w-full h-full object-contain" />
              </div>
              <span className="text-2xl font-heading font-bold text-white">
                DistroZone
              </span>
            </div>
            <p className="text-gray-300 leading-relaxed">
              Toko kaos distro josjis dengan koleksi lengkap dan berkualitas.
              Melayani pembelian online maupun offline.
            </p>
            <div className="flex space-x-4">
              {/* Social Media Icons */}
              <a
                href="https://www.facebook.com/kafka.sanjaya.3"
                className="w-10 h-10 rounded-full bg-gray-700 hover:bg-cyan-600 flex items-center justify-center transition-colors"
                aria-label="Github"
              >
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
              </a>
              <a
                href="https://www.instagram.com/kafkahmd/"
                className="w-10 h-10 rounded-full bg-gray-700 hover:bg-cyan-600 flex items-center justify-center transition-colors"
                aria-label="Instagram"
              >
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </a>
              <a
                href="https://www.linkedin.com/in/kafka-ahmad-sanjaya-110404318?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app"
                className="w-10 h-10 rounded-full bg-gray-700 hover:bg-cyan-600 flex items-center justify-center transition-colors"
                aria-label="Linkedin"
              >
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M22.23 0H1.77C.79 0 0 .774 0 1.727v20.545C0 23.227.79 24 1.77 24h20.46c.98 0 1.77-.773 1.77-1.728V1.727C24 .774 23.21 0 22.23 0zM7.09 20.452H3.56V9h3.53v11.452zM5.325 7.433c-1.13 0-2.048-.926-2.048-2.065 0-1.138.918-2.064 2.048-2.064 1.13 0 2.048.926 2.048 2.064 0 1.139-.918 2.065-2.048 2.065zM20.452 20.452h-3.53v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667h-3.53V9h3.389v1.561h.047c.472-.9 1.623-1.85 3.34-1.85 3.57 0 4.23 2.35 4.23 5.408v6.333z" />
                </svg>
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-lg font-heading font-semibold mb-6 text-cyan-400">
              Navigasi
            </h3>
            <ul className="space-y-3">
              <li>
                <Link to="/" className="text-gray-300 hover:text-cyan-400 transition-colors flex items-center gap-2">
                  <span className="w-1.5 h-1.5 bg-cyan-500 rounded-full"></span>
                  Home
                </Link>
              </li>
              <li>
                <Link to="/products" className="text-gray-300 hover:text-cyan-400 transition-colors flex items-center gap-2">
                  <span className="w-1.5 h-1.5 bg-cyan-500 rounded-full"></span>
                  Produk
                </Link>
              </li>
              <li>
                <Link to="/orders" className="text-gray-300 hover:text-cyan-400 transition-colors flex items-center gap-2">
                  <span className="w-1.5 h-1.5 bg-cyan-500 rounded-full"></span>
                  Pesanan Saya
                </Link>
              </li>
              <li>
                <Link to="/chat" className="text-gray-300 hover:text-cyan-400 transition-colors flex items-center gap-2">
                  <span className="w-1.5 h-1.5 bg-cyan-500 rounded-full"></span>
                  Customer Service
                </Link>
              </li>
            </ul>
          </div>

          {/* Contact Info */}
          <div>
            <h3 className="text-lg font-heading font-semibold mb-6 text-cyan-400">
              Kontak Kami
            </h3>
            <ul className="space-y-4">
              <li className="flex items-start gap-3 text-gray-300">
                <MapPinIcon className="h-5 w-5 text-cyan-500 flex-shrink-0 mt-1" />
                <span>Jln. Raya Pegangsaan Timur No.29H Kelapa Gading Jakarta</span>
              </li>
              <li className="flex items-center gap-3 text-gray-300">
                <PhoneIcon className="h-5 w-5 text-cyan-500 flex-shrink-0" />
                <span>+62 812 3456 7890</span>
              </li>
              <li className="flex items-center gap-3 text-gray-300">
                <EnvelopeIcon className="h-5 w-5 text-cyan-500 flex-shrink-0" />
                <span>info@distrozone.com</span>
              </li>
            </ul>
          </div>

          {/* Operational Hours */}
          <div>
            <h3 className="text-lg font-heading font-semibold mb-6 text-cyan-400">
              Jam Operasional
            </h3>
            <div className="space-y-4">
              <div className="flex items-start gap-3 text-gray-300">
                <ClockIcon className="h-5 w-5 text-cyan-500 flex-shrink-0 mt-1" />
                <div>
                  <p className="font-medium text-white">Online Shop</p>
                  <p className="text-sm">Setiap Hari</p>
                  <p className="text-sm">10:00 - 17:00 WIB</p>
                  <p className="text-xs text-cyan-400 mt-1">Tanpa Hari Libur</p>
                </div>
              </div>
              <div className="p-4 bg-cyan-500/10 border border-cyan-500/20 rounded-lg">
                <p className="text-sm text-cyan-300">
                  💬 Customer Service tersedia sesuai jam operasional
                </p>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="mt-12 pt-8 border-t border-gray-700">
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <p className="text-gray-400 text-sm">
              © {currentYear} <span className="text-cyan-400 font-semibold">DistroZone</span>. All rights reserved.
            </p>
            <div className="flex items-center gap-6 text-sm text-gray-400">
              <a href="#" className="hover:text-cyan-400 transition-colors">
                Kebijakan Privasi
              </a>
              <a href="#" className="hover:text-cyan-400 transition-colors">
                Syarat & Ketentuan
              </a>
              <a href="#" className="hover:text-cyan-400 transition-colors">
                Panduan Pengiriman
              </a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;