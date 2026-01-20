import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import {
  ClockIcon,
  CheckCircleIcon,
  TruckIcon,
  XCircleIcon,
  PhotoIcon,
} from '@heroicons/react/24/outline';
import apiClient from '../services/apiClient';
import Loading from '../components/Loading';
import Badge from '../components/Badge';
import Button from '../components/Button';
import Modal from '../components/Modal';
import { formatCurrency, formatDateTime } from '../utils/formatters';
import { useUser } from '../contexts/UserContext';

interface Order {
  id: number;
  order_code: string;
  subtotal: number;
  shipping_cost: number;
  total_payment: number;
  destination_city: string;
  payment_status: string;
  order_status: string;
  payment_method: string;
  payment_proof: string | null;
  payment_proof_url: string | null;
  created_at: string;
  order_details: Array<{
    product_detail: {
      product: {
        name: string;
      };
    };
    quantity: number;
    unit_price: number;
  }>;
}

const OrderHistory: React.FC = () => {
  const navigate = useNavigate();
  const { user, isLoading } = useUser();
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState<string>('all');
  const [selectedOrder, setSelectedOrder] = useState<Order | null>(null);
  const [showModal, setShowModal] = useState(false);
  const [showCancelModal, setShowCancelModal] = useState(false);
  const [canceling, setCanceling] = useState(false);

  useEffect(() => {
    if (!isLoading) {
      if (!user) {
        navigate('/login');
        return;
      }
      fetchOrders();
    }
  }, [user, isLoading]);

  const fetchOrders = async () => {
    try {
      const response = await apiClient.get('/orders');
      const data = response.data.data;
      setOrders(Array.isArray(data) ? data : (data?.data || []));
    } catch (error) {
      console.error('Failed to fetch orders:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleCancelOrder = async () => {
    if (!selectedOrder) return;

    setCanceling(true);
    try {
      await apiClient.post(`/orders/${selectedOrder.id}/cancel`);
      alert('Pesanan berhasil dibatalkan');
      setShowCancelModal(false);
      setSelectedOrder(null);
      fetchOrders();
    } catch (error) {
      console.error('Failed to cancel order:', error);
      alert('Gagal membatalkan pesanan. Silakan coba lagi.');
    } finally {
      setCanceling(false);
    }
  };

  const getStatusBadge = (status: string) => {
    const statusMap: Record<string, { variant: any; label: string; icon: any }> = {
      pending: { variant: 'warning', label: 'Menunggu Verifikasi', icon: ClockIcon },
      verified: { variant: 'info', label: 'Terverifikasi', icon: CheckCircleIcon },
      shipped: { variant: 'primary', label: 'Dalam Pengiriman', icon: TruckIcon },
      completed: { variant: 'success', label: 'Selesai', icon: CheckCircleIcon },
      cancelled: { variant: 'error', label: 'Dibatalkan', icon: XCircleIcon },
    };

    const config = statusMap[status] || statusMap.pending;
    const Icon = config.icon;

    return (
      <Badge variant={config.variant} className="flex items-center gap-1">
        <Icon className="h-4 w-4" />
        {config.label}
      </Badge>
    );
  };

  const filteredOrders = filter === 'all'
    ? orders
    : orders.filter(order => order.order_status === filter);

  if (loading || isLoading) {
    return <Loading text="Memuat riwayat pesanan..." />;
  }

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="container-custom max-w-5xl">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-heading font-bold text-gray-900 mb-2">
            Riwayat Pesanan
          </h1>
          <p className="text-gray-600">
            Lihat dan pantau status pesanan Anda
          </p>
        </div>

        {/* Filters */}
        <div className="bg-white rounded-xl shadow-sm p-4 mb-6">
          <div className="flex flex-wrap gap-2">
            <button
              onClick={() => setFilter('all')}
              className={`px-4 py-2 rounded-lg font-medium transition-colors ${filter === 'all'
                ? 'bg-cyan-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
            >
              Semua
            </button>
            <button
              onClick={() => setFilter('pending')}
              className={`px-4 py-2 rounded-lg font-medium transition-colors ${filter === 'pending'
                ? 'bg-cyan-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
            >
              Menunggu Verifikasi
            </button>
            <button
              onClick={() => setFilter('verified')}
              className={`px-4 py-2 rounded-lg font-medium transition-colors ${filter === 'verified'
                ? 'bg-cyan-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
            >
              Terverifikasi
            </button>
            <button
              onClick={() => setFilter('shipped')}
              className={`px-4 py-2 rounded-lg font-medium transition-colors ${filter === 'shipped'
                ? 'bg-cyan-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
            >
              Dalam Pengiriman
            </button>
            <button
              onClick={() => setFilter('completed')}
              className={`px-4 py-2 rounded-lg font-medium transition-colors ${filter === 'completed'
                ? 'bg-cyan-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
            >
              Selesai
            </button>
            <button
              onClick={() => setFilter('cancelled')}
              className={`px-4 py-2 rounded-lg font-medium transition-colors ${filter === 'cancelled'
                ? 'bg-cyan-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                }`}
            >
              Dibatalkan
            </button>
          </div>
        </div>

        {/* Orders List */}
        {filteredOrders.length === 0 ? (
          <div className="bg-white rounded-xl shadow-sm p-12 text-center">
            <div className="text-6xl mb-4">📦</div>
            <h3 className="text-xl font-heading font-semibold text-gray-900 mb-2">
              Belum Ada Pesanan
            </h3>
            <p className="text-gray-600 mb-6">
              {filter === 'all'
                ? 'Anda belum memiliki pesanan. Mulai belanja sekarang!'
                : `Tidak ada pesanan dengan status "${filter}"`}
            </p>
            <Button onClick={() => navigate('/products')} variant="primary">
              Mulai Belanja
            </Button>
          </div>
        ) : (
          <div className="space-y-4">
            {filteredOrders.map((order) => (
              <div
                key={order.id}
                className="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow"
              >
                <div className="flex flex-wrap items-start justify-between gap-4 mb-4">
                  <div>
                    <div className="flex items-center gap-3 mb-2">
                      <h3 className="text-lg font-heading font-semibold text-gray-900">
                        {order.order_code}
                      </h3>
                      {getStatusBadge(order.order_status)}
                    </div>
                    <p className="text-sm text-gray-600">
                      {formatDateTime(order.created_at)}
                    </p>
                  </div>
                  <div className="text-right">
                    <p className="text-lg font-bold text-cyan-600">
                      {formatCurrency(order.total_payment)}
                    </p>
                    <p className="text-xs text-gray-500">
                      {order.payment_method.toUpperCase()}
                    </p>
                  </div>
                </div>

                {/* Order Items Preview */}
                <div className="mb-4 py-4 border-t border-b border-gray-200">
                  {Array.isArray(order.order_details) && order.order_details.slice(0, 2).map((detail, index) => (
                    <p key={index} className="text-sm text-gray-600">
                      • {detail.product_detail?.product?.name || 'Produk'} × {detail.quantity}
                    </p>
                  ))}
                  {Array.isArray(order.order_details) && order.order_details.length > 2 && (
                    <p className="text-sm text-gray-500">
                      +{order.order_details.length - 2} produk lainnya
                    </p>
                  )}
                </div>

                <div className="flex flex-wrap gap-3">
                  <Button
                    onClick={() => {
                      setSelectedOrder(order);
                      setShowModal(true);
                    }}
                    variant="outline"
                    size="sm"
                  >
                    Lihat Detail
                  </Button>

                  {order.order_status === 'pending' && (
                    <Button
                      onClick={() => {
                        setSelectedOrder(order);
                        setShowCancelModal(true);
                      }}
                      variant="outline"
                      size="sm"
                      className="text-red-600 border-red-200 hover:bg-red-50"
                    >
                      <XCircleIcon className="h-4 w-4" />
                      Batalkan Pesanan
                    </Button>
                  )}

                  {order.order_status === 'pending' && !order.payment_proof && (
                    <Button variant="primary" size="sm">
                      <PhotoIcon className="h-4 w-4" />
                      Upload Bukti Bayar
                    </Button>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Order Detail Modal */}
        <Modal
          isOpen={showModal}
          onClose={() => setShowModal(false)}
          title="Detail Pesanan"
          size="lg"
        >
          {selectedOrder && (
            <div className="space-y-6">
              <div>
                <h4 className="font-semibold text-gray-900 mb-2">Kode Pesanan</h4>
                <p className="text-gray-700">{selectedOrder.order_code}</p>
              </div>

              <div>
                <h4 className="font-semibold text-gray-900 mb-2">Status</h4>
                <div>{getStatusBadge(selectedOrder.order_status)}</div>
              </div>

              <div>
                <h4 className="font-semibold text-gray-900 mb-2">Alamat Tujuan</h4>
                <p className="text-gray-700">{selectedOrder.destination_city}</p>
              </div>

              <div>
                <h4 className="font-semibold text-gray-900 mb-3">Produk yang Dibeli</h4>
                <div className="space-y-2">
                  {Array.isArray(selectedOrder.order_details) && selectedOrder.order_details.map((detail, index) => (
                    <div key={index} className="flex justify-between text-sm">
                      <span className="text-gray-700">
                        {detail.product_detail?.product?.name || 'Produk'} × {detail.quantity}
                      </span>
                      <span className="font-medium">
                        {formatCurrency(detail.unit_price * detail.quantity)}
                      </span>
                    </div>
                  ))}
                </div>
              </div>

              <div className="border-t border-gray-200 pt-4 space-y-2">
                <div className="flex justify-between text-sm">
                  <span className="text-gray-600">Subtotal</span>
                  <span className="font-medium">{formatCurrency(selectedOrder.subtotal)}</span>
                </div>
                <div className="flex justify-between text-sm">
                  <span className="text-gray-600">Ongkos Kirim</span>
                  <span className="font-medium">{formatCurrency(selectedOrder.shipping_cost)}</span>
                </div>
                <div className="flex justify-between text-lg font-bold">
                  <span>Total</span>
                  <span className="text-cyan-600">{formatCurrency(selectedOrder.total_payment)}</span>
                </div>
              </div>

              {selectedOrder.payment_proof && (
                <div>
                  <h4 className="font-semibold text-gray-900 mb-2">Bukti Pembayaran</h4>
                  <img
                    src={selectedOrder.payment_proof_url || ''}
                    alt="Bukti Pembayaran"
                    className="rounded-lg max-h-64"
                  />
                </div>
              )}
            </div>
          )}
        </Modal>

        {/* Cancel Confirmation Modal */}
        <Modal
          isOpen={showCancelModal}
          onClose={() => !canceling && setShowCancelModal(false)}
          title="Konfirmasi Pembatalan"
          size="sm"
        >
          <div className="text-center">
            <div className="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <XCircleIcon className="h-8 w-8 text-red-600" />
            </div>
            <h3 className="text-lg font-bold text-gray-900 mb-2">Batalkan Pesanan?</h3>
            <p className="text-sm text-gray-600 mb-6">
              Apakah Anda yakin ingin membatalkan pesanan <span className="font-semibold">{selectedOrder?.order_code}</span>?
              Tindakan ini tidak dapat dibatalkan dan stok produk akan dikembalikan.
            </p>
            <div className="grid grid-cols-2 gap-3">
              <Button
                variant="outline"
                onClick={() => setShowCancelModal(false)}
                disabled={canceling}
              >
                Batal
              </Button>
              <Button
                variant="primary"
                className="bg-red-600 hover:bg-red-700 border-red-600"
                onClick={handleCancelOrder}
                loading={canceling}
              >
                Ya, Batalkan
              </Button>
            </div>
          </div>
        </Modal>
      </div>
    </div>
  );
};

export default OrderHistory;