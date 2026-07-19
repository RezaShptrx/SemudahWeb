import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import { Plus, Edit, Trash2, X, PlusCircle, MinusCircle, Copy, Layout } from 'lucide-react';

const Products = () => {
    const navigate = useNavigate();
    const [products, setProducts] = useState([]);
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [modalOpen, setModalOpen] = useState(false);
    
    // Form State
    const [formData, setFormData] = useState({ 
        id: null, name: '', slug: '', category_id: '', description: '', 
        base_price: '', unit: 'pcs', is_active: true, requires_file_upload: false,
        image: null, image_url: ''
    });
    const [specifications, setSpecifications] = useState([]);

    // Templates definition
    const templates = {
        print: [
            { spec_name: 'Ukuran Cetak', spec_value: 'A4', price_modifier: 0 },
            { spec_name: 'Bahan', spec_value: 'Art Carton 260gr', price_modifier: 0 },
            { spec_name: 'Sisi Cetak', spec_value: '1 Sisi', price_modifier: 0 },
            { spec_name: 'Finishing', spec_value: 'Tanpa Laminasi', price_modifier: 0 },
        ],
        custom: [
            { spec_name: 'Warna Dasar', spec_value: 'Putih', price_modifier: 0 },
            { spec_name: 'Area Sablon', spec_value: 'Depan (A4)', price_modifier: 0 },
        ]
    };

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            const token = localStorage.getItem('token');
            const [prodRes, catRes] = await Promise.all([
                axios.get('/api/products', { headers: { Authorization: `Bearer ${token}` } }),
                axios.get('/api/categories', { headers: { Authorization: `Bearer ${token}` } })
            ]);
            setProducts(prodRes.data);
            setCategories(catRes.data);
        } catch (err) {
            console.error('Error fetching data:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleOpenModal = (product = null) => {
        if (product) {
            setFormData({
                id: product.id,
                name: product.name,
                slug: product.slug,
                category_id: product.category_id,
                description: product.description || '',
                base_price: product.base_price,
                unit: product.unit,
                is_active: product.is_active,
                requires_file_upload: product.requires_file_upload,
                image: null,
                image_url: product.image_url || ''
            });
            setSpecifications(product.specifications || []);
        } else {
            setFormData({ 
                id: null, name: '', slug: '', category_id: categories.length > 0 ? categories[0].id : '', 
                description: '', base_price: '', unit: 'pcs', is_active: true, requires_file_upload: false,
                image: null, image_url: ''
            });
            setSpecifications([]);
        }
        setModalOpen(true);
    };

    const handleApplyTemplate = (type) => {
        if (templates[type]) {
            // Clone the template to avoid reference issues
            setSpecifications(JSON.parse(JSON.stringify(templates[type])));
            if (type === 'print') setFormData(prev => ({ ...prev, requires_file_upload: true }));
        }
    };

    const handleAddSpec = () => {
        setSpecifications([...specifications, { spec_name: '', spec_value: '', price_modifier: 0 }]);
    };

    const handleRemoveSpec = (index) => {
        const newSpecs = [...specifications];
        newSpecs.splice(index, 1);
        setSpecifications(newSpecs);
    };

    const handleSpecChange = (index, field, value) => {
        const newSpecs = [...specifications];
        newSpecs[index][field] = value;
        setSpecifications(newSpecs);
    };

    const handleChange = (e) => {
        const { name, value, type, checked, files } = e.target;
        if (type === 'file') {
            setFormData(prev => ({ ...prev, [name]: files[0] }));
        } else {
            setFormData(prev => ({
                ...prev,
                [name]: type === 'checkbox' ? checked : value,
                ...(name === 'name' && !prev.id ? { slug: value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '') } : {})
            }));
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const token = localStorage.getItem('token');
            const headers = { Authorization: `Bearer ${token}` };
            
            const payload = new FormData();
            Object.keys(formData).forEach(key => {
                if (key === 'image' && !formData[key]) return; // Skip if no new image
                if (key === 'image_url') return; // Don't send this back
                if (key === 'is_active' || key === 'requires_file_upload') {
                    payload.append(key, formData[key] ? 1 : 0);
                } else if (formData[key] !== null && formData[key] !== undefined) {
                    payload.append(key, formData[key]);
                }
            });

            const validSpecs = specifications.filter(s => s.spec_name.trim() !== '' && s.spec_value.trim() !== '');
            payload.append('specifications', JSON.stringify(validSpecs));

            if (formData.id) {
                payload.append('_method', 'PUT'); // Laravel requirement for FormData PUT
                await axios.post(`/api/products/${formData.id}`, payload, { headers: { ...headers, 'Content-Type': 'multipart/form-data' } });
            } else {
                await axios.post('/api/products', payload, { headers: { ...headers, 'Content-Type': 'multipart/form-data' } });
            }
            fetchData();
            setModalOpen(false);
        } catch (err) {
            alert('Error saving product. Check your inputs.');
            console.error(err);
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Are you sure you want to delete this product?')) return;
        
        try {
            const token = localStorage.getItem('token');
            await axios.delete(`/api/products/${id}`, {
                headers: { Authorization: `Bearer ${token}` }
            });
            fetchData();
        } catch (err) {
            alert('Error deleting product.');
            console.error(err);
        }
    };

    if (loading) return <div className="text-center py-10">Loading...</div>;

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h2 className="text-2xl font-bold text-slate-800 dark:text-white">Products</h2>
                    <p className="text-slate-500 text-sm mt-1">Manage products and templates</p>
                </div>
                <button 
                    onClick={() => handleOpenModal()}
                    className="flex items-center gap-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white px-4 py-2 rounded-xl shadow-md transition-transform hover:-translate-y-0.5"
                >
                    <Plus size={18} /> Add Product
                </button>
            </div>

            <div className="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead className="bg-gray-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 uppercase text-xs font-semibold">
                            <tr>
                                <th className="px-6 py-4">Product Name</th>
                                <th className="px-6 py-4">Category</th>
                                <th className="px-6 py-4">Base Price</th>
                                <th className="px-6 py-4">Unit</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 dark:divide-slate-700">
                            {products.map(prod => (
                                <tr key={prod.id} className="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td className="px-6 py-4 flex items-center gap-3">
                                        {prod.image_url ? (
                                            <img src={prod.image_url} alt={prod.name} className="w-10 h-10 rounded-lg object-cover border border-gray-200" />
                                        ) : (
                                            <div className="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                <Layout size={16} />
                                            </div>
                                        )}
                                        <div>
                                            <div className="font-bold text-slate-900 dark:text-white">{prod.name}</div>
                                            <div className="text-xs text-slate-500">{prod.slug}</div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-xs">
                                            {prod.category?.name || 'Uncategorized'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 font-medium text-cyan-600 dark:text-cyan-400">
                                        Rp {parseInt(prod.base_price).toLocaleString()}
                                    </td>
                                    <td className="px-6 py-4">{prod.unit}</td>
                                    <td className="px-6 py-4">
                                        <span className={`px-2.5 py-1 rounded-lg text-xs font-semibold ${prod.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'}`}>
                                            {prod.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right flex justify-end items-center gap-1">
                                        <button onClick={() => navigate(`/admin/products/${prod.id}/form`)} className="text-purple-500 hover:text-purple-700 p-2" title="Edit Form">
                                            <Layout size={18} />
                                        </button>
                                        <button onClick={() => handleOpenModal(prod)} className="text-blue-500 hover:text-blue-700 p-2" title="Edit Data">
                                            <Edit size={18} />
                                        </button>
                                        <button onClick={() => handleDelete(prod.id)} className="text-red-500 hover:text-red-700 p-2" title="Delete">
                                            <Trash2 size={18} />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {products.length === 0 && (
                                <tr><td colSpan="6" className="px-6 py-8 text-center text-slate-500">No products found.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal */}
            {modalOpen && (
                <div className="fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4 overflow-y-auto font-sans">
                    <div className="bg-white dark:bg-slate-800 rounded-[12px] w-full max-w-4xl shadow-xl overflow-hidden my-8 max-h-[90vh] flex flex-col border border-semudah-primary/10">
                        <div className="flex items-center justify-between p-6 border-b border-semudah-primary/10 dark:border-slate-700 bg-white dark:bg-slate-800 shrink-0">
                            <h3 className="text-xl font-heading font-bold text-slate-800 dark:text-white">
                                {formData.id ? 'Edit Product' : 'Add Product'}
                            </h3>
                            <button onClick={() => setModalOpen(false)} className="text-slate-400 hover:text-slate-600">
                                <X size={24} />
                            </button>
                        </div>

                        <div className="overflow-y-auto flex-1 p-6">
                            <form id="productForm" onSubmit={handleSubmit} className="space-y-8">
                                {/* Basic Info Section */}
                                <div className="space-y-4">
                                    <h4 className="font-heading font-bold text-lg border-b pb-2 dark:border-slate-700 text-slate-800 dark:text-white border-semudah-primary/10">1. Basic Information</h4>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Product Name</label>
                                            <input type="text" name="name" value={formData.name} onChange={handleChange} required
                                                className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none" />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Slug</label>
                                            <input type="text" name="slug" value={formData.slug} onChange={handleChange} required
                                                className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none" />
                                        </div>
                                    </div>
                                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Category</label>
                                            <select name="category_id" value={formData.category_id} onChange={handleChange} required
                                                className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none">
                                                <option value="">Select Category</option>
                                                {categories.map(cat => <option key={cat.id} value={cat.id}>{cat.name}</option>)}
                                            </select>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Base Price (Rp)</label>
                                            <input type="number" name="base_price" value={formData.base_price} onChange={handleChange} required min="0"
                                                className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none" />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Unit (e.g. pcs, rim)</label>
                                            <input type="text" name="unit" value={formData.unit} onChange={handleChange} required
                                                className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none" />
                                        </div>
                                    </div>
                                    <div>
                                        <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Description</label>
                                        <textarea name="description" value={formData.description} onChange={handleChange} rows="2"
                                            className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none" />
                                    </div>
                                    <div className="grid grid-cols-1 gap-4">
                                        <div>
                                            <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Product Image</label>
                                            <div className="flex items-center gap-4">
                                                {formData.image_url && !formData.image && (
                                                    <img src={formData.image_url} alt="Product" className="w-16 h-16 rounded-[8px] object-cover border border-gray-200" />
                                                )}
                                                {formData.image && (
                                                    <div className="w-16 h-16 rounded-[8px] bg-semudah-secondary/20 flex items-center justify-center text-semudah-anchor text-xs text-center border border-semudah-secondary/30">New Image</div>
                                                )}
                                                <input type="file" name="image" accept="image/*" onChange={handleChange}
                                                    className="w-full px-4 py-2.5 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-[8px] file:border-0 file:text-sm file:font-bold file:bg-semudah-secondary/20 file:text-semudah-anchor hover:file:bg-semudah-secondary/30" />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-6 pt-2">
                                        <div className="flex items-center">
                                            <input type="checkbox" name="is_active" id="prod_is_active" checked={formData.is_active} onChange={handleChange}
                                                className="w-5 h-5 rounded text-semudah-secondary border-gray-300 focus:ring-semudah-secondary" />
                                            <label htmlFor="prod_is_active" className="ml-2 text-sm font-bold text-slate-700 dark:text-slate-300">Product is Active</label>
                                        </div>
                                        <div className="flex items-center">
                                            <input type="checkbox" name="requires_file_upload" id="requires_file" checked={formData.requires_file_upload} onChange={handleChange}
                                                className="w-5 h-5 rounded text-semudah-secondary border-gray-300 focus:ring-semudah-secondary" />
                                            <label htmlFor="requires_file" className="ml-2 text-sm font-bold text-slate-700 dark:text-slate-300">Requires File Upload</label>
                                        </div>
                                    </div>
                                </div>

                                {/* Specifications / Template Section */}
                                <div className="space-y-4">
                                    <div className="flex items-center justify-between border-b pb-2 border-semudah-primary/10 dark:border-slate-700">
                                        <h4 className="font-heading font-bold text-lg text-slate-800 dark:text-white">2. Specifications & Variants</h4>
                                        <div className="flex gap-2">
                                            <button type="button" onClick={() => handleApplyTemplate('print')} className="text-xs font-bold font-sans bg-semudah-secondary/10 text-semudah-anchor dark:bg-indigo-900/30 dark:text-indigo-400 px-3 py-1.5 rounded-[8px] hover:bg-semudah-secondary/20 flex items-center gap-1">
                                                <Copy size={14} /> Template Print
                                            </button>
                                            <button type="button" onClick={() => handleApplyTemplate('custom')} className="text-xs font-bold font-sans bg-semudah-secondary/10 text-semudah-anchor dark:bg-fuchsia-900/30 dark:text-fuchsia-400 px-3 py-1.5 rounded-[8px] hover:bg-semudah-secondary/20 flex items-center gap-1">
                                                <Copy size={14} /> Template Custom
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div className="bg-gray-50 dark:bg-slate-700/30 p-4 rounded-[12px] border border-semudah-primary/10 dark:border-slate-700 space-y-3">
                                        {specifications.length === 0 ? (
                                            <p className="text-sm text-slate-500 text-center py-4">No specifications defined. Click 'Add Row' or use a Template.</p>
                                        ) : (
                                            specifications.map((spec, index) => (
                                                <div key={index} className="flex flex-col sm:flex-row gap-3 items-end">
                                                    <div className="flex-1 w-full">
                                                        <label className="block text-xs font-semibold text-slate-500 mb-1">Spec Name</label>
                                                        <input type="text" value={spec.spec_name} onChange={(e) => handleSpecChange(index, 'spec_name', e.target.value)} placeholder="e.g. Ukuran"
                                                            className="w-full px-3 py-2 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-1 focus:ring-semudah-primary outline-none" />
                                                    </div>
                                                    <div className="flex-1 w-full">
                                                        <label className="block text-xs font-semibold text-slate-500 mb-1">Spec Value</label>
                                                        <input type="text" value={spec.spec_value} onChange={(e) => handleSpecChange(index, 'spec_value', e.target.value)} placeholder="e.g. A4"
                                                            className="w-full px-3 py-2 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-1 focus:ring-semudah-primary outline-none" />
                                                    </div>
                                                    <div className="flex-1 w-full">
                                                        <label className="block text-xs font-semibold text-slate-500 mb-1">Additional Price (Rp)</label>
                                                        <input type="number" value={spec.price_modifier} onChange={(e) => handleSpecChange(index, 'price_modifier', e.target.value)} placeholder="0"
                                                            className="w-full px-3 py-2 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-1 focus:ring-semudah-primary outline-none" />
                                                    </div>
                                                    <button type="button" onClick={() => handleRemoveSpec(index)} className="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-[8px] mb-0.5">
                                                        <MinusCircle size={20} />
                                                    </button>
                                                </div>
                                            ))
                                        )}
                                        <button type="button" onClick={handleAddSpec} className="flex items-center gap-1 text-semudah-primary text-sm font-bold hover:text-semudah-primary/90 p-1">
                                            <PlusCircle size={16} /> Add Row
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div className="p-6 border-t border-semudah-primary/10 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 shrink-0 flex justify-end gap-3 rounded-b-[12px]">
                            <button type="button" onClick={() => setModalOpen(false)} className="px-6 py-2.5 rounded-[8px] font-sans text-slate-600 bg-white border border-gray-200 hover:bg-slate-50 font-bold transition">
                                Cancel
                            </button>
                            <button type="submit" form="productForm" className="px-6 py-2.5 rounded-[8px] font-sans text-white bg-semudah-primary hover:bg-semudah-primary/90 font-bold transition shadow-sm">
                                Save Product
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Products;
