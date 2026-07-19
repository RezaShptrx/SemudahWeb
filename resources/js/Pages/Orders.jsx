import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { FileText, Eye, CheckCircle, Clock, XCircle, Printer, Edit } from 'lucide-react';

const Orders = () => {
    const [orders, setOrders] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedOrder, setSelectedOrder] = useState(null);
    const [statusModalOpen, setStatusModalOpen] = useState(false);
    
    // Status update form
    const [updateForm, setUpdateForm] = useState({ status: '', payment_status: '', internal_notes: '' });

    useEffect(() => {
        fetchOrders();
    }, []);

    const fetchOrders = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get('/api/orders', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setOrders(res.data);
        } catch (err) {
            console.error('Error fetching orders:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleOpenStatusModal = (order) => {
        setSelectedOrder(order);
        setUpdateForm({
            status: order.status,
            payment_status: order.payment_status,
            internal_notes: order.internal_notes || ''
        });
        setStatusModalOpen(true);
    };

    const handleUpdateStatus = async (e) => {
        e.preventDefault();
        try {
            const token = localStorage.getItem('token');
            await axios.put(`/api/orders/${selectedOrder.id}`, updateForm, {
                headers: { Authorization: `Bearer ${token}` }
            });
            fetchOrders();
            setStatusModalOpen(false);
        } catch (err) {
            const msg = err.response?.data?.message || 'Error updating order status.';
            alert(msg);
            console.error(err);
        }
    };

    const getStatusColor = (status) => {
        switch(status) {
            case 'selesai': return 'bg-semudah-secondary/20 text-semudah-anchor dark:bg-green-900/30 dark:text-green-400';
            case 'dibatalkan': return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
            case 'diproses': return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
            case 'menunggu_antrian': return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
            default: return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
        }
    };

    if (loading) return <div className="text-center py-10">Loading...</div>;

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h2 className="text-2xl font-heading font-bold text-slate-800 dark:text-white">Orders</h2>
                    <p className="text-slate-500 font-sans text-sm mt-1">Manage customer print & merchandise orders</p>
                </div>
            </div>

            <div className="bg-white dark:bg-slate-800 rounded-[12px] shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-semudah-primary/10 dark:border-slate-700 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300 font-sans">
                        <thead className="bg-gray-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 uppercase text-xs font-semibold">
                            <tr>
                                <th className="px-6 py-4">Order ID & Date</th>
                                <th className="px-6 py-4">Customer</th>
                                <th className="px-6 py-4">Items</th>
                                <th className="px-6 py-4">Total</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 dark:divide-slate-700">
                            {orders.map(order => (
                                <tr key={order.id} className="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td className="px-6 py-4">
                                        <div className="font-heading font-bold text-slate-900 dark:text-white">{order.order_number}</div>
                                        <div className="text-xs text-slate-500 font-sans">{new Date(order.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year:'numeric', hour:'2-digit', minute:'2-digit'})}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="font-heading font-bold text-slate-800 dark:text-slate-200">{order.customer_name}</div>
                                        <div className="text-xs text-slate-500">{order.customer_phone}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="text-sm">{order.items?.length || 0} items</div>
                                        <div className="text-xs text-slate-500 truncate max-w-[150px]">
                                            {order.items?.map(i => i.product?.name).join(', ')}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 font-heading font-bold text-semudah-primary dark:text-semudah-accent">
                                        Rp {parseInt(order.final_price).toLocaleString()}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-col gap-1 items-start">
                                            <span className={`px-2.5 py-1 rounded-[4px] text-[10px] font-bold uppercase ${getStatusColor(order.status)}`}>
                                                {order.status.replace(/_/g, ' ')}
                                            </span>
                                            <span className={`px-2.5 py-1 rounded-[4px] text-[10px] font-bold uppercase ${order.payment_status === 'lunas' ? 'bg-semudah-secondary/20 text-semudah-anchor' : 'bg-red-100 text-red-700'}`}>
                                                {order.payment_status}
                                            </span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <button onClick={() => handleOpenStatusModal(order)} className="text-semudah-primary hover:text-semudah-primary/90 bg-semudah-secondary/10 p-2 rounded-[8px] transition-colors">
                                            <Edit size={18} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {orders.length === 0 && (
                                <tr><td colSpan="6" className="px-6 py-8 text-center text-slate-500">No orders found.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Status Update Modal */}
            {statusModalOpen && selectedOrder && (
                <div className="fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4 font-sans">
                    <div className="bg-white dark:bg-slate-800 rounded-[12px] w-full max-w-md shadow-xl overflow-hidden border border-semudah-primary/10">
                        <div className="flex items-center justify-between p-6 border-b border-semudah-primary/10 dark:border-slate-700">
                            <h3 className="text-xl font-heading font-bold text-slate-800 dark:text-white">
                                Update Order <span className="text-semudah-primary">{selectedOrder.order_number}</span>
                            </h3>
                            <button onClick={() => setStatusModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                                <XCircle size={24} />
                            </button>
                        </div>
                        <form onSubmit={handleUpdateStatus} className="p-6 space-y-4">
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Order Status</label>
                                <select 
                                    value={updateForm.status} 
                                    onChange={(e) => setUpdateForm({...updateForm, status: e.target.value})}
                                    disabled={selectedOrder.status === 'selesai'}
                                    className={`w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none ${selectedOrder.status === 'selesai' ? 'bg-gray-200 dark:bg-slate-800 opacity-70 cursor-not-allowed' : 'bg-gray-50 dark:bg-slate-700'}`}
                                >
                                    <option value="menunggu_antrian">Menunggu Antrian</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                                {selectedOrder.status === 'selesai' && (
                                    <p className="text-xs text-red-500 mt-1">Status pesanan yang sudah selesai tidak dapat diubah.</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Payment Status</label>
                                <select 
                                    value={updateForm.payment_status} 
                                    onChange={(e) => setUpdateForm({...updateForm, payment_status: e.target.value})}
                                    className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none"
                                >
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="sebagian">Sebagian (DP)</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Internal Notes</label>
                                <textarea 
                                    value={updateForm.internal_notes} 
                                    onChange={(e) => setUpdateForm({...updateForm, internal_notes: e.target.value})} rows="3"
                                    className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none"
                                    placeholder="Private notes for staff..."
                                ></textarea>
                            </div>
                            
                            <div className="pt-4 flex justify-end gap-3">
                                <button type="button" onClick={() => setStatusModalOpen(false)} className="px-5 py-2.5 rounded-[8px] text-slate-600 bg-slate-100 hover:bg-slate-200 font-bold transition">
                                    Cancel
                                </button>
                                <button type="submit" className="px-5 py-2.5 rounded-[8px] text-white bg-semudah-primary hover:bg-semudah-primary/90 font-bold transition shadow-sm">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Orders;
