import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Plus, Edit, Trash2, X } from 'lucide-react';

const Services = () => {
    const [services, setServices] = useState([]);
    const [loading, setLoading] = useState(true);
    const [modalOpen, setModalOpen] = useState(false);
    const [formData, setFormData] = useState({ id: null, name: '', slug: '', description: '', icon: '' });
    
    useEffect(() => {
        fetchServices();
    }, []);

    const fetchServices = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get('/api/services', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setServices(res.data);
        } catch (err) {
            console.error('Error fetching services:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleOpenModal = (service = null) => {
        if (service) {
            setFormData(service);
        } else {
            setFormData({ id: null, name: '', slug: '', description: '', icon: '' });
        }
        setModalOpen(true);
    };

    const handleCloseModal = () => {
        setModalOpen(false);
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value,
            ...(name === 'name' && !prev.id ? { slug: value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '') } : {})
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const token = localStorage.getItem('token');
            const headers = { Authorization: `Bearer ${token}` };
            
            if (formData.id) {
                await axios.put(`/api/services/${formData.id}`, formData, { headers });
            } else {
                await axios.post('/api/services', formData, { headers });
            }
            fetchServices();
            handleCloseModal();
        } catch (err) {
            alert('Error saving service. Please check your inputs.');
            console.error(err);
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Are you sure you want to delete this service?')) return;
        
        try {
            const token = localStorage.getItem('token');
            await axios.delete(`/api/services/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            fetchServices();
        } catch (err) {
            alert('Error deleting service.');
            console.error(err);
        }
    };

    if (loading) return <div className="text-center py-10">Loading...</div>;

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h2 className="text-2xl font-heading font-bold text-slate-800 dark:text-white">Services</h2>
                    <p className="text-slate-500 font-sans text-sm mt-1">Manage main service lines</p>
                </div>
                <button 
                    onClick={() => handleOpenModal()}
                    className="flex items-center gap-2 bg-semudah-primary hover:bg-semudah-primary/90 text-white px-4 py-2 rounded-[8px] font-sans font-bold shadow-sm transition-transform hover:-translate-y-0.5"
                >
                    <Plus size={18} /> Add Service
                </button>
            </div>

            <div className="bg-white dark:bg-slate-800 rounded-[12px] shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-semudah-primary/10 dark:border-slate-700 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300 font-sans">
                        <thead className="bg-gray-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 uppercase text-xs font-semibold">
                            <tr>
                                <th className="px-6 py-4">Name</th>
                                <th className="px-6 py-4">Slug</th>
                                <th className="px-6 py-4">Description</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 dark:divide-slate-700">
                            {services.map(srv => (
                                <tr key={srv.id} className="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td className="px-6 py-4 font-heading font-bold text-slate-900 dark:text-white">{srv.name}</td>
                                    <td className="px-6 py-4"><span className="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 rounded-[4px] text-xs font-sans">{srv.slug}</span></td>
                                    <td className="px-6 py-4 max-w-xs truncate">{srv.description || '-'}</td>
                                    <td className="px-6 py-4 text-right">
                                        <button onClick={() => handleOpenModal(srv)} className="text-blue-500 hover:text-blue-700 p-2">
                                            <Edit size={18} />
                                        </button>
                                        <button onClick={() => handleDelete(srv.id)} className="text-red-500 hover:text-red-700 p-2">
                                            <Trash2 size={18} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {services.length === 0 && (
                                <tr>
                                    <td colSpan="4" className="px-6 py-8 text-center text-slate-500">No services found.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal */}
            {modalOpen && (
                <div className="fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-800 rounded-[12px] border border-semudah-primary/10 font-sans w-full max-w-lg shadow-xl overflow-hidden">
                        <div className="flex items-center justify-between p-6 border-b border-semudah-primary/10 dark:border-slate-700">
                            <h3 className="text-xl font-heading font-bold text-slate-800 dark:text-white">
                                {formData.id ? 'Edit Service' : 'Add Service'}
                            </h3>
                            <button onClick={handleCloseModal} className="text-slate-400 hover:text-slate-600">
                                <X size={24} />
                            </button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Name</label>
                                <input 
                                    type="text" name="name" value={formData.name} onChange={handleChange} required
                                    className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none transition" 
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Slug</label>
                                <input 
                                    type="text" name="slug" value={formData.slug} onChange={handleChange} required
                                    className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none transition" 
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Description</label>
                                <textarea 
                                    name="description" value={formData.description || ''} onChange={handleChange} rows="3"
                                    className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none transition" 
                                ></textarea>
                            </div>
                            <div className="pt-4 flex justify-end gap-3">
                                <button type="button" onClick={handleCloseModal} className="px-5 py-2.5 rounded-[8px] font-sans font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                                    Cancel
                                </button>
                                <button type="submit" className="px-5 py-2.5 rounded-[8px] font-sans font-bold text-white bg-semudah-primary hover:bg-semudah-primary/90 transition shadow-sm">
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

export default Services;
