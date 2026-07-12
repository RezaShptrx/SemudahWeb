import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Plus, Edit, Trash2, X } from 'lucide-react';

const PromoCodes = () => {
    const [promoCodes, setPromoCodes] = useState([]);
    const [loading, setLoading] = useState(true);
    const [modalOpen, setModalOpen] = useState(false);
    const [formData, setFormData] = useState({ 
        id: null, code: '', discount_type: 'percentage', discount_amount: '', 
        min_purchase: '', valid_from: '', valid_until: '', is_active: true, max_uses: '' 
    });
    
    useEffect(() => {
        fetchPromoCodes();
    }, []);

    const fetchPromoCodes = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get('/api/promo-codes', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setPromoCodes(res.data);
        } catch (err) {
            console.error('Error fetching promo codes:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleOpenModal = (promo = null) => {
        if (promo) {
            setFormData({
                ...promo,
                valid_from: promo.valid_from ? promo.valid_from.split(' ')[0] : '',
                valid_until: promo.valid_until ? promo.valid_until.split(' ')[0] : '',
            });
        } else {
            setFormData({ 
                id: null, code: '', discount_type: 'percentage', discount_amount: '', 
                min_purchase: '', valid_from: '', valid_until: '', is_active: true, max_uses: '' 
            });
        }
        setModalOpen(true);
    };

    const handleCloseModal = () => {
        setModalOpen(false);
    };

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const token = localStorage.getItem('token');
            const headers = { Authorization: `Bearer ${token}` };
            
            const payload = { ...formData };
            if (!payload.min_purchase) delete payload.min_purchase;
            if (!payload.max_uses) delete payload.max_uses;
            if (!payload.valid_from) delete payload.valid_from;
            if (!payload.valid_until) delete payload.valid_until;

            if (formData.id) {
                await axios.put(`/api/promo-codes/${formData.id}`, payload, { headers });
            } else {
                await axios.post('/api/promo-codes', payload, { headers });
            }
            fetchPromoCodes();
            handleCloseModal();
        } catch (err) {
            alert('Error saving promo code. Check your inputs.');
            console.error(err);
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Are you sure you want to delete this promo code?')) return;
        
        try {
            const token = localStorage.getItem('token');
            await axios.delete(`/api/promo-codes/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            fetchPromoCodes();
        } catch (err) {
            alert('Error deleting promo code.');
            console.error(err);
        }
    };

    if (loading) return <div className="text-center py-10">Loading...</div>;

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h2 className="text-2xl font-bold text-slate-800 dark:text-white">Promo Codes</h2>
                    <p className="text-slate-500 text-sm mt-1">Manage discounts and campaigns</p>
                </div>
                <button 
                    onClick={() => handleOpenModal()}
                    className="flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white px-4 py-2 rounded-xl shadow-md transition-transform hover:-translate-y-0.5"
                >
                    <Plus size={18} /> Add Promo
                </button>
            </div>

            <div className="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead className="bg-gray-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 uppercase text-xs font-semibold">
                            <tr>
                                <th className="px-6 py-4">Code</th>
                                <th className="px-6 py-4">Discount</th>
                                <th className="px-6 py-4">Validity</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 dark:divide-slate-700">
                            {promoCodes.map(promo => (
                                <tr key={promo.id} className="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td className="px-6 py-4 font-bold text-slate-900 dark:text-white tracking-wide">
                                        <span className="px-2.5 py-1 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 border border-cyan-100 dark:border-cyan-800 rounded-lg">
                                            {promo.code}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 font-medium">
                                        {promo.discount_type === 'percentage' ? `${promo.discount_amount}%` : `Rp ${parseInt(promo.discount_amount).toLocaleString()}`}
                                    </td>
                                    <td className="px-6 py-4 text-xs">
                                        <div>From: {promo.valid_from ? promo.valid_from.split(' ')[0] : '-'}</div>
                                        <div>Until: {promo.valid_until ? promo.valid_until.split(' ')[0] : '-'}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`px-2.5 py-1 rounded-lg text-xs font-semibold ${promo.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}`}>
                                            {promo.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <button onClick={() => handleOpenModal(promo)} className="text-blue-500 hover:text-blue-700 p-2">
                                            <Edit size={18} />
                                        </button>
                                        <button onClick={() => handleDelete(promo.id)} className="text-red-500 hover:text-red-700 p-2">
                                            <Trash2 size={18} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal */}
            {modalOpen && (
                <div className="fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
                    <div className="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-lg shadow-xl overflow-hidden my-8">
                        <div className="flex items-center justify-between p-6 border-b border-gray-100 dark:border-slate-700">
                            <h3 className="text-xl font-bold text-slate-800 dark:text-white">
                                {formData.id ? 'Edit Promo Code' : 'Add Promo Code'}
                            </h3>
                            <button onClick={handleCloseModal} className="text-slate-400 hover:text-slate-600">
                                <X size={24} />
                            </button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Code</label>
                                <input 
                                    type="text" name="code" value={formData.code} onChange={handleChange} required uppercase="true"
                                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition uppercase" 
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Discount Type</label>
                                    <select 
                                        name="discount_type" value={formData.discount_type} onChange={handleChange}
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition"
                                    >
                                        <option value="percentage">Percentage (%)</option>
                                        <option value="fixed">Fixed Amount (Rp)</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Amount</label>
                                    <input 
                                        type="number" name="discount_amount" value={formData.discount_amount} onChange={handleChange} required min="0"
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                    />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Min Purchase</label>
                                    <input 
                                        type="number" name="min_purchase" value={formData.min_purchase} onChange={handleChange} min="0" placeholder="Optional"
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Max Uses</label>
                                    <input 
                                        type="number" name="max_uses" value={formData.max_uses} onChange={handleChange} min="1" placeholder="Optional"
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                    />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Valid From</label>
                                    <input 
                                        type="date" name="valid_from" value={formData.valid_from} onChange={handleChange}
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Valid Until</label>
                                    <input 
                                        type="date" name="valid_until" value={formData.valid_until} onChange={handleChange}
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                    />
                                </div>
                            </div>
                            <div className="flex items-center pt-2">
                                <input 
                                    type="checkbox" name="is_active" checked={formData.is_active} onChange={handleChange}
                                    className="w-5 h-5 rounded text-cyan-500 border-gray-300 focus:ring-cyan-500" 
                                    id="promo_is_active"
                                />
                                <label htmlFor="promo_is_active" className="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Active Status</label>
                            </div>
                            
                            <div className="pt-4 flex justify-end gap-3">
                                <button type="button" onClick={handleCloseModal} className="px-5 py-2.5 rounded-xl text-slate-600 bg-slate-100 hover:bg-slate-200 font-medium transition">
                                    Cancel
                                </button>
                                <button type="submit" className="px-5 py-2.5 rounded-xl text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 font-medium transition shadow-md">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default PromoCodes;
