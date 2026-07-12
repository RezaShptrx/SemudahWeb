import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Plus, Edit, Trash2, X } from 'lucide-react';

const Users = () => {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [modalOpen, setModalOpen] = useState(false);
    const [formData, setFormData] = useState({ id: null, name: '', email: '', password: '', phone: '', role: 'customer', is_active: true });
    
    useEffect(() => {
        fetchUsers();
    }, []);

    const fetchUsers = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get('/api/users', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setUsers(res.data);
        } catch (err) {
            console.error('Error fetching users:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleOpenModal = (user = null) => {
        if (user) {
            setFormData({ ...user, password: '' }); // Don't populate password hash
        } else {
            setFormData({ id: null, name: '', email: '', password: '', phone: '', role: 'customer', is_active: true });
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
            
            if (formData.id) {
                await axios.put(`/api/users/${formData.id}`, formData, { headers });
            } else {
                await axios.post('/api/users', formData, { headers });
            }
            fetchUsers();
            handleCloseModal();
        } catch (err) {
            alert('Error saving user. Check your inputs.');
            console.error(err);
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Are you sure you want to delete this user?')) return;
        
        try {
            const token = localStorage.getItem('token');
            await axios.delete(`/api/users/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            fetchUsers();
        } catch (err) {
            alert('Error deleting user.');
            console.error(err);
        }
    };

    if (loading) return <div className="text-center py-10">Loading...</div>;

    const roleColors = {
        admin: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        petugas: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
        customer: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    };

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h2 className="text-2xl font-bold text-slate-800 dark:text-white">Users</h2>
                    <p className="text-slate-500 text-sm mt-1">Manage system access and roles</p>
                </div>
                <button 
                    onClick={() => handleOpenModal()}
                    className="flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white px-4 py-2 rounded-xl shadow-md transition-transform hover:-translate-y-0.5"
                >
                    <Plus size={18} /> Add User
                </button>
            </div>

            <div className="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead className="bg-gray-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 uppercase text-xs font-semibold">
                            <tr>
                                <th className="px-6 py-4">Name</th>
                                <th className="px-6 py-4">Email / Phone</th>
                                <th className="px-6 py-4">Role</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 dark:divide-slate-700">
                            {users.map(user => (
                                <tr key={user.id} className="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td className="px-6 py-4 font-medium text-slate-900 dark:text-white">{user.name}</td>
                                    <td className="px-6 py-4">
                                        <div className="text-slate-800 dark:text-slate-200">{user.email}</div>
                                        <div className="text-xs text-slate-500">{user.phone || 'No phone'}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`px-2.5 py-1 rounded-lg text-xs font-semibold ${roleColors[user.role]}`}>
                                            {user.role}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`px-2.5 py-1 rounded-lg text-xs font-semibold ${user.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}`}>
                                            {user.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <button onClick={() => handleOpenModal(user)} className="text-blue-500 hover:text-blue-700 p-2">
                                            <Edit size={18} />
                                        </button>
                                        <button onClick={() => handleDelete(user.id)} className="text-red-500 hover:text-red-700 p-2">
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
                                {formData.id ? 'Edit User' : 'Add User'}
                            </h3>
                            <button onClick={handleCloseModal} className="text-slate-400 hover:text-slate-600">
                                <X size={24} />
                            </button>
                        </div>
                        <form onSubmit={handleSubmit} className="p-6 space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
                                <input 
                                    type="text" name="name" value={formData.name} onChange={handleChange} required
                                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                    <input 
                                        type="email" name="email" value={formData.email} onChange={handleChange} required
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone</label>
                                    <input 
                                        type="text" name="phone" value={formData.phone || ''} onChange={handleChange}
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                    />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                    Password {formData.id && <span className="text-xs text-gray-400 font-normal">(Leave blank to keep current)</span>}
                                </label>
                                <input 
                                    type="password" name="password" value={formData.password} onChange={handleChange} 
                                    required={!formData.id} minLength="8"
                                    className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition" 
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Role</label>
                                    <select 
                                        name="role" value={formData.role} onChange={handleChange}
                                        className="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-2 focus:ring-cyan-500 outline-none transition"
                                    >
                                        <option value="customer">Customer</option>
                                        <option value="petugas">Petugas</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div className="flex items-center mt-6">
                                    <input 
                                        type="checkbox" name="is_active" checked={formData.is_active} onChange={handleChange}
                                        className="w-5 h-5 rounded text-cyan-500 border-gray-300 focus:ring-cyan-500" 
                                        id="is_active"
                                    />
                                    <label htmlFor="is_active" className="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Active Account</label>
                                </div>
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

export default Users;
