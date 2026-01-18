import React, { useEffect, useState, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import {
  PaperAirplaneIcon,
  ClockIcon,
} from '@heroicons/react/24/outline';
import apiClient from '../services/apiClient';
import Button from '../components/Button';
import Badge from '../components/Badge';
import { formatTime } from '../utils/formatters';
import { useUser } from '../contexts/UserContext';

interface Message {
  id: number;
  message: string;
  sender_type: 'customer' | 'kasir';
  created_at: string;
}

const CustomerServiceChat: React.FC = () => {
  const navigate = useNavigate();
  const { user } = useUser();
  const messagesEndRef = useRef<HTMLDivElement>(null);

  const [messages, setMessages] = useState<Message[]>([]);
  const [newMessage, setNewMessage] = useState('');
  const [sending, setSending] = useState(false);
  const [isOperationalHours, setIsOperationalHours] = useState(true);

  useEffect(() => {
    if (!user) {
      navigate('/login');
      return;
    }

    checkOperationalHours();
    fetchMessages();

    // Poll for new messages every 5 seconds
    const interval = setInterval(fetchMessages, 5000);
    return () => clearInterval(interval);
  }, [user]);

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const checkOperationalHours = () => {
    const now = new Date();
    const currentHour = now.getHours();

    // Operational hours: 10:00 - 17:00
    setIsOperationalHours(currentHour >= 10 && currentHour < 17);
  };

  const fetchMessages = async () => {
    try {
      const response = await apiClient.get('/chat/messages');
      setMessages(response.data.data || []);
    } catch (error) {
      console.error('Failed to fetch messages:', error);
    }
  };

  const handleSendMessage = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!newMessage.trim()) return;

    setSending(true);
    try {
      await apiClient.post('/chat/send', {
        message: newMessage.trim(),
      });
      setNewMessage('');
      fetchMessages();
    } catch (error) {
      console.error('Failed to send message:', error);
      alert('Gagal mengirim pesan');
    } finally {
      setSending(false);
    }
  };

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container-custom max-w-4xl py-8">
        {/* Header */}
        <div className="bg-white rounded-t-xl shadow-sm p-6">
          <div className="flex items-center justify-between">
            <div>
              <h1 className="text-2xl font-heading font-bold text-gray-900 mb-1">
                Customer Service
              </h1>
              <div className="flex items-center gap-2">
                {isOperationalHours ? (
                  <>
                    <span className="flex h-2 w-2">
                      <span className="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                      <span className="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <p className="text-sm text-green-600 font-medium">Online</p>
                  </>
                ) : (
                  <>
                    <span className="inline-flex h-2 w-2 rounded-full bg-gray-400"></span>
                    <p className="text-sm text-gray-600">Offline</p>
                  </>
                )}
              </div>
            </div>
            <div className="text-right">
              <div className="flex items-center gap-2 text-cyan-600">
                <ClockIcon className="h-5 w-5" />
                <div className="text-sm">
                  <p className="font-medium">Jam Operasional</p>
                  <p className="text-gray-600">10:00 - 17:00 WIB</p>
                </div>
              </div>
            </div>
          </div>

          {!isOperationalHours && (
            <div className="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
              <p className="text-sm text-yellow-800">
                📩 Customer service sedang offline. Anda tetap dapat mengirim pesan dan kami akan membalas saat jam operasional.
              </p>
            </div>
          )}
        </div>

        {/* Messages Area */}
        <div className="bg-white border-x border-gray-200 p-6 h-[500px] overflow-y-auto scrollbar-thin">
          {messages.length === 0 ? (
            <div className="flex items-center justify-center h-full text-center">
              <div>
                <div className="text-6xl mb-4">💬</div>
                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                  Belum Ada Pesan
                </h3>
                <p className="text-gray-600">
                  Mulai percakapan dengan customer service kami
                </p>
              </div>
            </div>
          ) : (
            <div className="space-y-4">
              {messages.map((message) => (
                <div
                  key={message.id}
                  className={`flex ${message.sender_type === 'customer' ? 'justify-end' : 'justify-start'
                    } animate-fade-in`}
                >
                  <div
                    className={`max-w-[70%] ${message.sender_type === 'customer'
                        ? 'bg-cyan-500 text-white rounded-l-xl rounded-tr-xl'
                        : 'bg-gray-100 text-gray-900 rounded-r-xl rounded-tl-xl'
                      } px-4 py-3 shadow-sm`}
                  >
                    {message.sender_type === 'kasir' && (
                      <p className="text-xs font-semibold mb-1 text-cyan-600">
                        CS DistroZone
                      </p>
                    )}
                    <p className="text-sm leading-relaxed">{message.message}</p>
                    <p
                      className={`text-xs mt-1 ${message.sender_type === 'customer'
                          ? 'text-cyan-100'
                          : 'text-gray-500'
                        }`}
                    >
                      {formatTime(message.created_at)}
                    </p>
                  </div>
                </div>
              ))}
              <div ref={messagesEndRef} />
            </div>
          )}
        </div>

        {/* Input Area */}
        <div className="bg-white rounded-b-xl shadow-sm p-6 border-t border-gray-200">
          <form onSubmit={handleSendMessage} className="flex gap-3">
            <input
              type="text"
              value={newMessage}
              onChange={(e) => setNewMessage(e.target.value)}
              placeholder={
                isOperationalHours
                  ? 'Ketik pesan Anda...'
                  : 'Pesan akan dibalas saat jam operasional...'
              }
              className="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 outline-none transition-all"
            />
            <Button
              type="submit"
              variant="primary"
              loading={sending}
              disabled={!newMessage.trim()}
            >
              <PaperAirplaneIcon className="h-5 w-5 rotate-90" />
              Kirim
            </Button>
          </form>
          <p className="text-xs text-gray-500 mt-3">
            Pesan Anda akan direspons oleh customer service kami selama jam operasional (10:00 - 17:00 WIB)
          </p>
        </div>

        {/* Info Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
          <div className="bg-white rounded-xl shadow-sm p-6">
            <h3 className="font-heading font-semibold text-gray-900 mb-3">
              📞 Kontak Kami
            </h3>
            <div className="space-y-2 text-sm text-gray-600">
              <p>Phone: +62 812 3456 7890</p>
              <p>Email: info@distrozone.com</p>
            </div>
          </div>

          <div className="bg-white rounded-xl shadow-sm p-6">
            <h3 className="font-heading font-semibold text-gray-900 mb-3">
              🏪 Lokasi Toko
            </h3>
            <p className="text-sm text-gray-600">
              Jln. Raya Pegangsaan Timur No.29H Kelapa Gading Jakarta
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CustomerServiceChat;