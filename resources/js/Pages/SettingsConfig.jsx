import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import { Save, Phone, MapPin, MessageSquare, Mail, AlertCircle, Building, Hash, Image, CreditCard } from 'lucide-react';

export default function SettingsConfig() {
    const fileInputRef = useRef(null);
    const [settings, setSettings] = useState({
        contact_whatsapp: '',
        contact_email: '',
        company_address: '',
        company_name: '',
        fonnte_template_order: '',
        order_number_prefix: 'SMDH',
        payment_qris_fee: 0,
        payment_qris_image: ''
    });
    
    const [qrisImageFile, setQrisImageFile] = useState(null);
    
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });

    useEffect(() => {
        fetchSettings();
    }, []);

    const fetchSettings = async () => {
        try {
            const response = await axios.get('/api/settings');
            const data = response.data;
            setSettings(prev => ({ ...prev, ...data }));
        } catch (error) {
            console.error('Failed to fetch settings:', error);
            setMessage({ type: 'error', text: 'Gagal memuat pengaturan.' });
        } finally {
            setLoading(false);
        }
    };

    const handleChange = (e) => {
        const { name, value, type } = e.target;
        setSettings(prev => ({
            ...prev,
            [name]: type === 'number' ? Number(value) : value
        }));
    };

    const handleFileChange = (e) => {
        if (e.target.files && e.target.files[0]) {
            setQrisImageFile(e.target.files[0]);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSaving(true);
        setMessage({ type: '', text: '' });

        const formData = new FormData();
        Object.keys(settings).forEach(key => {
            if (settings[key] !== null && settings[key] !== undefined) {
                formData.append(key, settings[key]);
            }
        });

        if (qrisImageFile) {
            formData.append('payment_qris_image', qrisImageFile);
        }

        try {
            await axios.post('/api/settings', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            setMessage({ type: 'success', text: 'Pengaturan berhasil disimpan!' });
            
            // Re-fetch to get updated image path
            fetchSettings();
            setQrisImageFile(null);
            if(fileInputRef.current) fileInputRef.current.value = '';
            
            // Clear success message after 3 seconds
            setTimeout(() => {
                setMessage({ type: '', text: '' });
            }, 3000);
        } catch (error) {
            console.error('Failed to save settings:', error);
            setMessage({ type: 'error', text: 'Terjadi kesalahan saat menyimpan pengaturan.' });
        } finally {
            setSaving(false);
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-cyan-600"></div>
            </div>
        );
    }

    return (
        <div className="max-w-4xl mx-auto">
            <div className="bg-white dark:bg-slate-800 rounded-[12px] shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-semudah-primary/10 dark:border-slate-700 overflow-hidden">
                <div className="px-6 py-5 border-b border-semudah-primary/10 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50">
                    <h2 className="text-xl font-heading font-bold text-slate-800 dark:text-white">Pengaturan Website</h2>
                    <p className="text-sm font-sans text-slate-500 mt-1">Kelola informasi kontak, alamat, dan konfigurasi sistem lainnya.</p>
                </div>
                
                <form onSubmit={handleSubmit} className="p-6">
                    {message.text && (
                        <div className={`mb-6 p-4 rounded-[12px] font-sans font-bold flex items-start ${
                            message.type === 'success' 
                                ? 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800' 
                                : 'bg-red-50 text-red-700 border border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800'
                        }`}>
                            <AlertCircle className="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" />
                            <p>{message.text}</p>
                        </div>
                    )}

                    <div className="space-y-8">
                        {/* Informasi Umum */}
                        <div>
                            <h3 className="text-lg font-heading font-bold text-slate-800 dark:text-white mb-4 border-b border-semudah-primary/10 dark:border-slate-700 pb-2">Informasi Umum</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                        Nama Perusahaan / Bisnis
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Building className="h-5 w-5 text-gray-400" />
                                        </div>
                                        <input
                                            type="text"
                                            name="company_name"
                                            value={settings.company_name || ''}
                                            onChange={handleChange}
                                            className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors"
                                            placeholder="PT Semudah Teknologi"
                                        />
                                    </div>
                                </div>
                                
                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                        Prefix Nomor Pesanan
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Hash className="h-5 w-5 text-gray-400" />
                                        </div>
                                        <input
                                            type="text"
                                            name="order_number_prefix"
                                            value={settings.order_number_prefix || ''}
                                            onChange={handleChange}
                                            className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors"
                                            placeholder="SMDH"
                                        />
                                    </div>
                                    <p className="mt-1 text-xs text-slate-500">Misal: SMDH akan menghasilkan order: SMDH-20260712-0001</p>
                                </div>
                            </div>
                        </div>

                        {/* Kontak & Alamat */}
                        <div>
                            <h3 className="text-lg font-heading font-bold text-slate-800 dark:text-white mb-4 border-b border-semudah-primary/10 dark:border-slate-700 pb-2">Kontak & Alamat</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                        Nomor WhatsApp Admin
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Phone className="h-5 w-5 text-gray-400" />
                                        </div>
                                        <input
                                            type="text"
                                            name="contact_whatsapp"
                                            value={settings.contact_whatsapp || ''}
                                            onChange={handleChange}
                                            className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors"
                                            placeholder="6281234567890"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                        Email Kontak
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Mail className="h-5 w-5 text-gray-400" />
                                        </div>
                                        <input
                                            type="email"
                                            name="contact_email"
                                            value={settings.contact_email || ''}
                                            onChange={handleChange}
                                            className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors"
                                            placeholder="admin@semudah.com"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                    Alamat Lengkap
                                </label>
                                <div className="relative">
                                    <div className="absolute top-3 left-3 pointer-events-none">
                                        <MapPin className="h-5 w-5 text-gray-400" />
                                    </div>
                                    <textarea
                                        name="company_address"
                                        value={settings.company_address || ''}
                                        onChange={handleChange}
                                        rows="3"
                                        className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors"
                                        placeholder="Jl. Contoh No. 123, Kota, Provinsi"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        {/* Metode Pembayaran (QRIS) */}
                        <div>
                            <h3 className="text-lg font-heading font-bold text-slate-800 dark:text-white mb-4 border-b border-semudah-primary/10 dark:border-slate-700 pb-2">Metode Pembayaran (QRIS)</h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                        Gambar Barcode QRIS
                                    </label>
                                    <div className="flex items-center gap-4">
                                        {settings.payment_qris_image && (
                                            <div className="w-16 h-16 rounded-[8px] border border-semudah-secondary/40 overflow-hidden flex-shrink-0 bg-white">
                                                <img src={settings.payment_qris_image} alt="QRIS" className="w-full h-full object-cover" />
                                            </div>
                                        )}
                                        <div className="relative flex-1">
                                            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <Image className="h-5 w-5 text-gray-400" />
                                            </div>
                                            <input
                                                type="file"
                                                accept="image/*"
                                                ref={fileInputRef}
                                                onChange={handleFileChange}
                                                className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-[4px] file:border-0 file:text-xs file:font-semibold file:bg-semudah-primary/10 file:text-semudah-primary hover:file:bg-semudah-primary/20"
                                            />
                                        </div>
                                    </div>
                                    <p className="mt-1 text-xs text-slate-500">Unggah gambar QRIS yang akan ditampilkan kepada pembeli (format: jpg, png, jpeg).</p>
                                </div>

                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                        Biaya Layanan/Admin QRIS (Rp)
                                    </label>
                                    <div className="relative">
                                        <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 font-semibold">
                                            Rp
                                        </div>
                                        <input
                                            type="number"
                                            name="payment_qris_fee"
                                            value={settings.payment_qris_fee || 0}
                                            onChange={handleChange}
                                            className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors"
                                            placeholder="1000"
                                        />
                                    </div>
                                    <p className="mt-1 text-xs text-slate-500">Nominal yang akan ditambahkan ke total tagihan pesanan.</p>
                                </div>
                            </div>
                        </div>

                        {/* Integrasi & Pesan */}
                        <div>
                            <h3 className="text-lg font-heading font-bold text-slate-800 dark:text-white mb-4 border-b border-semudah-primary/10 dark:border-slate-700 pb-2">Integrasi Fonnte (WhatsApp)</h3>
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-2">
                                    Template Pesan Pesanan Baru (Opsional)
                                </label>
                                <div className="relative">
                                    <div className="absolute top-3 left-3 pointer-events-none">
                                        <MessageSquare className="h-5 w-5 text-gray-400" />
                                    </div>
                                    <textarea
                                        name="fonnte_template_order"
                                        value={settings.fonnte_template_order || ''}
                                        onChange={handleChange}
                                        rows="5"
                                        className="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-semudah-secondary/40 text-slate-900 rounded-[8px] focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary font-sans dark:bg-slate-900 dark:border-slate-700 dark:text-white transition-colors font-mono text-sm"
                                        placeholder="Halo {name}, Pesanan Anda {order_number} telah kami terima..."
                                    ></textarea>
                                </div>
                                <p className="mt-2 text-xs text-slate-500">
                                    Anda dapat menggunakan variabel seperti <code className="bg-gray-100 dark:bg-slate-700 px-1 py-0.5 rounded text-cyan-600 dark:text-cyan-400">{'{name}'}</code>, <code className="bg-gray-100 dark:bg-slate-700 px-1 py-0.5 rounded text-cyan-600 dark:text-cyan-400">{'{order_number}'}</code>, <code className="bg-gray-100 dark:bg-slate-700 px-1 py-0.5 rounded text-cyan-600 dark:text-cyan-400">{'{total}'}</code>. Biarkan kosong untuk menggunakan template bawaan sistem.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="mt-8 pt-6 border-t border-semudah-primary/10 dark:border-slate-700 flex justify-end">
                        <button
                            type="submit"
                            disabled={saving}
                            className="bg-semudah-primary hover:bg-semudah-primary/90 text-white px-6 py-2.5 rounded-[8px] font-sans font-bold flex items-center transition-all shadow-sm shadow-semudah-primary/30 disabled:opacity-70 disabled:cursor-not-allowed"
                        >
                            {saving ? (
                                <div className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin mr-2"></div>
                            ) : (
                                <Save className="w-5 h-5 mr-2" />
                            )}
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
