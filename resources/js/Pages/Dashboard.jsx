import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { TrendingUp, ShoppingBag, DollarSign, Package, ExternalLink, CheckCircle } from 'lucide-react';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';
import { Link, useNavigate } from 'react-router-dom';

const Dashboard = () => {
    const navigate = useNavigate();
    const [stats, setStats] = useState({
        orders_today: 0,
        orders_this_month: 0,
        revenue_this_month: 0,
        active_products: 0
    });
    const [latestOrders, setLatestOrders] = useState([]);
    const [salesChart, setSalesChart] = useState([]);
    const [loading, setLoading] = useState(true);

    const rawUser = localStorage.getItem('user');
    const user = rawUser ? JSON.parse(rawUser) : null;
    const isAdmin = user?.role === 'admin';

    useEffect(() => {
        fetchDashboardData();
    }, []);

    const fetchDashboardData = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get('/api/reports/summary', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setStats(res.data.statistics);
            setLatestOrders(res.data.latest_orders);
            setSalesChart(res.data.sales_chart || []);
        } catch (err) {
            console.error('Error fetching dashboard stats:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleQuickProcess = (orderNumber) => {
        // Arahkan petugas ke halaman detail/orders untuk memproses
        navigate('/admin/orders');
    };

    if (loading) return <div className="text-center py-10 text-slate-500">Memuat dashboard...</div>;

    const statCards = [
        { title: 'Pesanan Hari Ini', value: stats.orders_today, icon: ShoppingBag, color: 'text-blue-500', bg: 'bg-blue-100 dark:bg-blue-900/30' },
        { title: 'Pesanan Bulan Ini', value: stats.orders_this_month, icon: TrendingUp, color: 'text-indigo-500', bg: 'bg-indigo-100 dark:bg-indigo-900/30' },
        { title: 'Pendapatan Bulan Ini', value: `Rp ${parseInt(stats.revenue_this_month).toLocaleString()}`, icon: DollarSign, color: 'text-green-500', bg: 'bg-green-100 dark:bg-green-900/30' },
        { title: 'Produk Aktif', value: stats.active_products, icon: Package, color: 'text-cyan-500', bg: 'bg-cyan-100 dark:bg-cyan-900/30' },
    ];

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-2xl font-bold text-slate-800 dark:text-white">
                    {isAdmin ? 'Dashboard Admin' : 'Dashboard Petugas'}
                </h2>
                <p className="text-slate-500 text-sm mt-1">Selamat datang kembali, {user?.name}</p>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {statCards.map((card, idx) => (
                    <div key={idx} className="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex items-center">
                        <div className={`p-4 rounded-2xl ${card.bg} ${card.color} mr-4`}>
                            <card.icon size={24} />
                        </div>
                        <div>
                            <div className="text-sm font-medium text-slate-500 dark:text-slate-400">{card.title}</div>
                            <div className="text-2xl font-bold text-slate-800 dark:text-white mt-1">{card.value}</div>
                        </div>
                    </div>
                ))}
            </div>

            <div className={`grid grid-cols-1 ${isAdmin ? 'lg:grid-cols-3' : 'lg:grid-cols-1'} gap-6`}>
                
                {/* Sales Chart (HANYA UNTUK ADMIN) */}
                {isAdmin && (
                    <div className="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 lg:col-span-2">
                        <h3 className="font-bold text-slate-800 dark:text-white mb-6">Tren Pendapatan (7 Hari Terakhir)</h3>
                        <div className="h-72 w-full">
                            <ResponsiveContainer width="100%" height="100%">
                                <LineChart data={salesChart}>
                                    <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#e2e8f0" />
                                    <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{fill: '#64748b'}} />
                                    <YAxis axisLine={false} tickLine={false} tick={{fill: '#64748b'}} tickFormatter={(val) => `Rp ${val / 1000}k`} />
                                    <Tooltip 
                                        formatter={(value) => [`Rp ${value.toLocaleString()}`, 'Pendapatan']}
                                        labelStyle={{ color: '#0f172a' }}
                                        contentStyle={{ borderRadius: '12px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}
                                    />
                                    <Line type="monotone" dataKey="revenue" stroke="#06b6d4" strokeWidth={3} dot={{r: 4, fill: '#06b6d4'}} activeDot={{r: 6}} />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                )}

                {/* Latest Orders Table (Untuk Admin ini jadi Sidebar Compact, untuk Petugas jadi Full Widget) */}
                <div className={`bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden ${isAdmin ? 'lg:col-span-1' : ''}`}>
                    <div className="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50">
                        <h3 className="font-bold text-slate-800 dark:text-white">
                            {isAdmin ? 'Transaksi Terbaru' : '⚡ Orderan Masuk'}
                        </h3>
                        {!isAdmin && (
                            <Link to="/admin/orders" className="text-sm font-medium text-cyan-600 hover:text-cyan-700 flex items-center gap-1">
                                Lihat Semua <ExternalLink size={14} />
                            </Link>
                        )}
                    </div>
                    
                    <div className="divide-y divide-gray-100 dark:divide-slate-700">
                        {latestOrders.map(order => (
                            <div key={order.id} className="p-5 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors flex justify-between items-center">
                                <div className="flex-1">
                                    <div className="font-bold text-slate-900 dark:text-white text-base">{order.customer_name}</div>
                                    <div className="flex items-center gap-2 mt-1">
                                        <span className="text-xs font-mono bg-slate-100 dark:bg-slate-600 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded">
                                            {order.order_number}
                                        </span>
                                        <span className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${
                                            order.status === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700 animate-pulse'
                                        }`}>
                                            {order.status.replace('_', ' ')}
                                        </span>
                                    </div>
                                </div>
                                <div className="text-right flex flex-col items-end gap-2">
                                    <div className="font-bold text-cyan-600 dark:text-cyan-400">
                                        Rp {parseInt(order.final_price).toLocaleString()}
                                    </div>
                                    {!isAdmin && order.status !== 'selesai' && (
                                        <button 
                                            onClick={() => handleQuickProcess(order.order_number)}
                                            className="text-xs bg-cyan-500 hover:bg-cyan-600 text-white px-3 py-1.5 rounded-lg flex items-center gap-1 shadow-sm transition-transform hover:-translate-y-0.5"
                                        >
                                            <CheckCircle size={14} /> Proses
                                        </button>
                                    )}
                                </div>
                            </div>
                        ))}
                        {latestOrders.length === 0 && (
                            <div className="p-8 text-center text-slate-500">Belum ada pesanan terbaru.</div>
                        )}
                    </div>
                </div>

            </div>
        </div>
    );
};

export default Dashboard;
