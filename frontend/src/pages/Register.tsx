import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import {
  EnvelopeIcon,
  LockClosedIcon,
  UserIcon,
  PhoneIcon,
  MapPinIcon,
  IdentificationIcon,
  ArrowRightIcon,
} from '@heroicons/react/24/outline';
import Button from '../components/Button';
import Input from '../components/Input';
import { useUser } from '../contexts/UserContext';
import { validatePassword, isValidEmail, isValidPhone } from '../utils/validation';

const Register: React.FC = () => {
  const navigate = useNavigate();
  const { register: registerUser } = useUser();

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    passwordConfirm: '',
    phone: '',
    address: '',
    nik: '',
  });

  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState<Record<string, string>>({});

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    // Clear error when user types
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: '' }));
    }
  };

  const validate = () => {
    const newErrors: Record<string, string> = {};

    if (!formData.name.trim()) {
      newErrors.name = 'Nama harus diisi';
    }

    if (!isValidEmail(formData.email)) {
      newErrors.email = 'Format email tidak valid';
    }

    const passwordValidation = validatePassword(formData.password);
    if (!passwordValidation.isValid) {
      newErrors.password = passwordValidation.errors[0];
    }

    if (formData.password !== formData.passwordConfirm) {
      newErrors.passwordConfirm = 'Password tidak cocok';
    }

    if (!isValidPhone(formData.phone)) {
      newErrors.phone = 'Format nomor telepon tidak valid';
    }

    if (!formData.address.trim()) {
      newErrors.address = 'Alamat harus diisi';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!validate()) {
      return;
    }

    setLoading(true);
    try {
      await registerUser({
        name: formData.name,
        email: formData.email,
        password: formData.password,
        password_confirmation: formData.passwordConfirm,
        phone: formData.phone,
        address: formData.address,
        nik: formData.nik,
      });
      alert('Registrasi berhasil! Silakan login.');
      navigate('/login');
    } catch (err: any) {
      const serverErrors = err.response?.data?.errors || {};
      setErrors(serverErrors);
      alert(err.response?.data?.message || 'Registrasi gagal');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-cyan-50 via-blue-50 to-purple-50 py-12 px-4">
      <div className="max-w-2xl mx-auto">
        {/* Header */}
        <div className="text-center mb-8 animate-fade-in-up">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-transparent mb-4">
            <img src="/logo.png" alt="DistroZone Logo" className="w-full h-full object-contain" />
          </div>
          <h1 className="text-3xl font-heading font-bold text-gray-900 mb-2">
            Daftar Akun Baru
          </h1>
          <p className="text-gray-600">
            Bergabung dengan DistroZone sekarang
          </p>
        </div>

        {/* Register Form */}
        <div className="bg-white rounded-2xl shadow-xl p-8 animate-fade-in-up">
          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <Input
                type="text"
                name="name"
                label="Nama Lengkap"
                value={formData.name}
                onChange={handleChange}
                icon={<UserIcon className="h-5 w-5" />}
                error={errors.name}
                required
              />

              <Input
                type="email"
                name="email"
                label="Email"
                value={formData.email}
                onChange={handleChange}
                icon={<EnvelopeIcon className="h-5 w-5" />}
                error={errors.email}
                required
              />

              <Input
                type="password"
                name="password"
                label="Password"
                value={formData.password}
                onChange={handleChange}
                icon={<LockClosedIcon className="h-5 w-5" />}
                error={errors.password}
                required
              />

              <Input
                type="password"
                name="passwordConfirm"
                label="Konfirmasi Password"
                value={formData.passwordConfirm}
                onChange={handleChange}
                icon={<LockClosedIcon className="h-5 w-5" />}
                error={errors.passwordConfirm}
                required
              />

              <Input
                type="tel"
                name="phone"
                label="Nomor Telepon"
                value={formData.phone}
                onChange={handleChange}
                icon={<PhoneIcon className="h-5 w-5" />}
                error={errors.phone}
                placeholder="08123456789"
                required
              />

              <Input
                type="text"
                name="nik"
                label="NIK (Opsional)"
                value={formData.nik}
                onChange={handleChange}
                icon={<IdentificationIcon className="h-5 w-5" />}
                error={errors.nik}
                placeholder="16 digit"
                maxLength={16}
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Alamat
              </label>
              <div className="relative">
                <MapPinIcon className="absolute left-3 top-3 h-5 w-5 text-gray-400" />
                <textarea
                  name="address"
                  value={formData.address}
                  onChange={handleChange}
                  className={`input pl-10 min-h-[100px] ${errors.address ? 'input-error' : ''}`}
                  placeholder="Alamat lengkap Anda..."
                  required
                />
              </div>
              {errors.address && <p className="mt-1 text-sm text-red-600">{errors.address}</p>}
            </div>

            {/* Password Requirements */}
            <div className="p-4 bg-gray-50 rounded-lg">
              <p className="text-sm text-gray-600 font-medium mb-2">Syarat Password:</p>
              <ul className="text-xs text-gray-600 space-y-1">
                <li>• Minimal 8 karakter</li>
                <li>• Mengandung huruf besar dan huruf kecil</li>
                <li>• Mengandung angka</li>
              </ul>
            </div>

            <Button
              type="submit"
              variant="primary"
              className="w-full"
              size="lg"
              loading={loading}
            >
              Daftar Sekarang
              <ArrowRightIcon className="h-5 w-5" />
            </Button>
          </form>

          {/* Divider */}
          <div className="relative my-6">
            <div className="absolute inset-0 flex items-center">
              <div className="w-full border-t border-gray-200"></div>
            </div>
            <div className="relative flex justify-center text-sm">
              <span className="px-4 bg-white text-gray-500">atau</span>
            </div>
          </div>

          {/* Login Link */}
          <div className="text-center">
            <p className="text-gray-600">
              Sudah punya akun?{' '}
              <Link to="/login" className="text-cyan-600 hover:text-cyan-700 font-semibold">
                Masuk Di Sini
              </Link>
            </p>
          </div>
        </div>

        {/* Back to Home */}
        <div className="text-center mt-6">
          <Link to="/" className="text-gray-600 hover:text-gray-900 text-sm">
            ← Kembali ke Beranda
          </Link>
        </div>
      </div>
    </div>
  );
};

export default Register;