import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';
import { Type, Hash, List, UploadCloud, ArrowUp, ArrowDown, Trash2, Plus, Save, ArrowLeft, CircleDot, CheckSquare, Heading, Calendar, Palette, X, Copy } from 'lucide-react';

const ProductFormBuilder = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [product, setProduct] = useState(null);
    const [schema, setSchema] = useState([]);
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [showTemplateMenu, setShowTemplateMenu] = useState(false);
    const [activeFieldId, setActiveFieldId] = useState(null);
    const [showAddMenu, setShowAddMenu] = useState(false);

    useEffect(() => {
        fetchProduct();
    }, [id]);

    const getDefaultSchema = (slug) => {
        const baseFile = { id: 'file_1', type: 'file', name: 'uploadedFiles', label: 'Upload Dokumen', required: true, accepted_types: '.pdf,.doc,.docx' };
        const baseQty = { id: 'qty_1', type: 'number', name: 'qty', label: 'Jumlah (Copy/Pcs)', required: true, min: 1 };
        
        switch(slug) {
            case 'jasa-print': return [baseFile, { id: 'opt_1', type: 'radio', name: 'warna', label: 'Warna', required: true, options: [{ label: 'Hitam Putih', value: 'Hitam Putih', price_modifier: 0 }, { label: 'Berwarna', value: 'Berwarna', price_modifier: 500 }]}, { id: 'opt_2', type: 'radio', name: 'ukuran', label: 'Ukuran Kertas', required: true, options: [{ label: 'A4', value: 'A4', price_modifier: 0 }, { label: 'F4', value: 'F4', price_modifier: 100 }]}, { id: 'opt_3', type: 'radio', name: 'bolak_balik', label: 'Bolak Balik', required: true, options: [{ label: 'Tidak', value: 'Tidak', price_modifier: 0 }, { label: 'Ya', value: 'Ya', price_modifier: 0 }]}, baseQty];
            case 'jasa-fotocopy': return [baseFile, { id: 'opt_1', type: 'radio', name: 'warna', label: 'Warna', required: true, options: [{ label: 'Hitam Putih', value: 'Hitam Putih', price_modifier: 0 }]}, { id: 'opt_3', type: 'radio', name: 'bolak_balik', label: 'Bolak Balik', required: true, options: [{ label: 'Tidak', value: 'Tidak', price_modifier: 0 }, { label: 'Ya', value: 'Ya', price_modifier: 0 }]}, baseQty];
            case 'custom-mug': return [
                { id: 'opt_1', type: 'radio', name: 'jenis_mug', label: 'Jenis Mug Custom', required: true, options: [
                    { label: 'Standar Putih', value: 'Standar Putih', price_modifier: 0 },
                    { label: 'Warna Dalam', value: 'Warna Dalam', price_modifier: 0 },
                    { label: 'Bunglon (Magic)', value: 'Bunglon (Magic)', price_modifier: 0 }
                ]},
                { ...baseFile, label: 'Upload Desain', accepted_types: '.png,.jpg,.jpeg' },
                { id: 'txt_1', type: 'textarea', name: 'catatan_khusus', label: 'Catatan Khusus', required: false },
                baseQty
            ];
            case 'custom-kaos': return [{ ...baseFile, label: 'Upload Desain Sablon', accepted_types: '.png,.jpg,.jpeg' }, { id: 'opt_1', type: 'select', name: 'warna_dasar', label: 'Warna Kaos', required: true, options: [{ label: 'Putih', value: 'Putih', price_modifier: 0 }, { label: 'Hitam', value: 'Hitam', price_modifier: 5000 }]}, { id: 'opt_2', type: 'radio', name: 'ukuran', label: 'Ukuran Kaos', required: true, options: [{ label: 'M', value: 'M', price_modifier: 0 }, { label: 'L', value: 'L', price_modifier: 0 }, { label: 'XL', value: 'XL', price_modifier: 5000 }]}, baseQty];
            case 'custom-tote-bag': return [{ ...baseFile, label: 'Upload Desain Totebag', accepted_types: '.png,.jpg,.jpeg' }, baseQty];
            case 'custom-emoney': return [{ ...baseFile, label: 'Upload Desain E-Money', accepted_types: '.png,.jpg,.jpeg' }, { id: 'opt_1', type: 'radio', name: 'jenis_kartu', label: 'Jenis Kartu', required: true, options: [{ label: 'Mandiri e-Money', value: 'Mandiri e-Money', price_modifier: 0 }, { label: 'BCA Flazz', value: 'BCA Flazz', price_modifier: 0 }, { label: 'BNI TapCash', value: 'BNI TapCash', price_modifier: 0 }, { label: 'BRI Brizzi', value: 'BRI Brizzi', price_modifier: 0 }]}, { id: 'opt_2', type: 'radio', name: 'cetak', label: 'Sisi Cetak', required: true, options: [{ label: '1 Sisi (Depan)', value: '1 Sisi', price_modifier: 0 }, { label: '2 Sisi (Depan & Belakang)', value: '2 Sisi', price_modifier: 15000 }]}, baseQty];
            case 'custom-gantungan-kunci': return [{ ...baseFile, label: 'Upload Desain / Logo', accepted_types: '.png,.jpg,.jpeg' }, { id: 'opt_1', type: 'radio', name: 'bentuk', label: 'Bentuk Gantungan', required: true, options: [{ label: 'Bulat', value: 'Bulat', price_modifier: 0 }, { label: 'Kotak', value: 'Kotak', price_modifier: 0 }, { label: 'Custom Shape', value: 'Custom Shape', price_modifier: 2000 }]}, { id: 'opt_2', type: 'radio', name: 'bahan', label: 'Bahan', required: true, options: [{ label: 'Akrilik 2mm', value: 'Akrilik 2mm', price_modifier: 0 }, { label: 'Akrilik 3mm', value: 'Akrilik 3mm', price_modifier: 1500 }]}, baseQty];
            default: return [];
        }
    };

    const loadTemplate = (slug) => {
        if (confirm('Terapkan template ini? Form saat ini akan tergantikan.')) {
            setSchema(getDefaultSchema(slug));
            setActiveFieldId(null);
        }
    };

    const fetchProduct = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get(`/api/products/${id}`, { headers: { Authorization: `Bearer ${token}` } });
            setProduct(res.data);
            if (res.data.form_schema && res.data.form_schema.length > 0) {
                setSchema(res.data.form_schema);
            } else {
                setSchema(getDefaultSchema(res.data.category?.slug));
            }
        } catch (err) {
            console.error('Error fetching product', err);
            alert('Gagal memuat produk');
        } finally {
            setLoading(false);
        }
    };

    const fieldTypes = [
        { type: 'text', icon: Type, label: 'Teks Pendek', default: { type: 'text', name: '', label: 'Pertanyaan Teks Pendek', required: true } },
        { type: 'textarea', icon: Type, label: 'Teks Panjang', default: { type: 'textarea', name: '', label: 'Pertanyaan Teks Panjang', required: false } },
        { type: 'number', icon: Hash, label: 'Angka', default: { type: 'number', name: '', label: 'Kuantitas/Angka', required: true, min: 1, default_value: 1 } },
        { type: 'select', icon: List, label: 'Dropdown', default: { type: 'select', name: '', label: 'Pilih Opsi', required: true, options: [{ label: 'Opsi 1', value: 'Opsi 1', price_modifier: 0 }] } },
        { type: 'radio', icon: CircleDot, label: 'Pilihan Tunggal', default: { type: 'radio', name: '', label: 'Pilihan Tunggal', required: true, options: [{ label: 'Opsi 1', value: 'Opsi 1', price_modifier: 0 }] } },
        { type: 'checkbox', icon: CheckSquare, label: 'Pilihan Jamak', default: { type: 'checkbox', name: '', label: 'Pilihan Jamak', required: false, options: [{ label: 'Opsi 1', value: 'Opsi 1', price_modifier: 0 }] } },
        { type: 'file', icon: UploadCloud, label: 'Upload File', default: { type: 'file', name: '', label: 'Upload Dokumen', required: true, accepted_types: '.pdf,.png,.jpg' } },
        { type: 'date', icon: Calendar, label: 'Tanggal', default: { type: 'date', name: '', label: 'Pilih Tanggal', required: true } },
        { type: 'color', icon: Palette, label: 'Pilih Warna', default: { type: 'color', name: '', label: 'Pilih Warna', required: true } },
        { type: 'header', icon: Heading, label: 'Teks Header', default: { type: 'header', name: 'header_' + Date.now(), label: 'Judul Bagian', description: 'Deskripsi bagian...', required: false } },
    ];

    const handleAddField = (template) => {
        const newField = { ...template, id: Date.now().toString() };
        newField.name = (newField.type + '_' + Math.floor(Math.random() * 1000));
        setSchema([...schema, newField]);
        setActiveFieldId(newField.id);
        setShowAddMenu(false);
    };

    const updateField = (index, updates) => {
        const newSchema = [...schema];
        newSchema[index] = { ...newSchema[index], ...updates };
        setSchema(newSchema);
    };

    const removeField = (index) => {
        const newSchema = [...schema];
        newSchema.splice(index, 1);
        setSchema(newSchema);
        setActiveFieldId(null);
    };

    const cloneField = (index) => {
        const fieldToClone = schema[index];
        const newField = JSON.parse(JSON.stringify(fieldToClone));
        newField.id = Date.now().toString();
        newField.name = newField.type + '_' + Math.floor(Math.random() * 1000);
        
        const newSchema = [...schema];
        newSchema.splice(index + 1, 0, newField);
        setSchema(newSchema);
        setActiveFieldId(newField.id);
    };

    const moveField = (index, direction) => {
        if (direction === 'up' && index === 0) return;
        if (direction === 'down' && index === schema.length - 1) return;
        
        const newSchema = [...schema];
        const targetIndex = direction === 'up' ? index - 1 : index + 1;
        const temp = newSchema[index];
        newSchema[index] = newSchema[targetIndex];
        newSchema[targetIndex] = temp;
        setSchema(newSchema);
    };

    const handleSave = async () => {
        setSaving(true);
        try {
            const token = localStorage.getItem('token');
            const payload = { ...product, form_schema: schema };
            await axios.put(`/api/products/${product.id}`, payload, { headers: { Authorization: `Bearer ${token}` } });
            alert('Form Builder berhasil disimpan!');
        } catch (err) {
            console.error(err);
            alert('Gagal menyimpan form');
        } finally {
            setSaving(false);
        }
    };

    // Render Field UI for "Preview" mode (Inactive)
    const renderFieldPreview = (field) => {
        return (
            <div className="w-full">
                {field.type !== 'header' && (
                    <label className="block text-base font-medium text-slate-800 dark:text-slate-200 mb-3">
                        {field.label} {field.required && <span className="text-red-500">*</span>}
                    </label>
                )}
                
                {field.type === 'header' && (
                    <div className="mb-2">
                        <h2 className="text-xl font-heading font-bold text-slate-800 dark:text-white">{field.label}</h2>
                        {field.description && <p className="text-slate-500 font-sans text-sm mt-1">{field.description}</p>}
                    </div>
                )}
                {field.type === 'text' && <input type="text" disabled className="w-full md:w-1/2 px-4 py-2 border-b border-gray-300 dark:border-slate-600 bg-transparent opacity-60" placeholder="Teks jawaban singkat" />}
                {field.type === 'textarea' && <textarea disabled className="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-[8px] bg-transparent opacity-60" rows="2" placeholder="Teks jawaban panjang" />}
                {field.type === 'number' && <input type="number" disabled className="w-full md:w-1/3 px-4 py-2 border-b border-gray-300 dark:border-slate-600 bg-transparent opacity-60" placeholder="Angka" />}
                {field.type === 'date' && <input type="date" disabled className="w-full md:w-1/3 px-4 py-2 border-b border-gray-300 dark:border-slate-600 bg-transparent opacity-60" />}
                {field.type === 'color' && <input type="color" disabled className="w-12 h-12 rounded cursor-not-allowed opacity-60" />}
                
                {field.type === 'select' && (
                    <select disabled className="w-full md:w-1/2 px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-[8px] bg-transparent opacity-60">
                        <option>Pilih salah satu</option>
                        {field.options?.map((opt, i) => <option key={i}>{opt.label}</option>)}
                    </select>
                )}
                {field.type === 'radio' && (
                    <div className="space-y-3 mt-2">
                        {field.options?.map((opt, i) => (
                            <div key={i} className="flex items-center gap-3 opacity-80">
                                <div className="w-5 h-5 rounded-full border-2 border-gray-400"></div>
                                <span className="text-slate-700 dark:text-slate-300">{opt.label}</span>
                            </div>
                        ))}
                    </div>
                )}
                {field.type === 'checkbox' && (
                    <div className="space-y-3 mt-2">
                        {field.options?.map((opt, i) => (
                            <div key={i} className="flex items-center gap-3 opacity-80">
                                <div className="w-5 h-5 rounded border-2 border-gray-400"></div>
                                <span className="text-slate-700 dark:text-slate-300">{opt.label}</span>
                            </div>
                        ))}
                    </div>
                )}
                {field.type === 'file' && (
                    <div className="w-full md:w-1/2 p-4 border border-gray-300 dark:border-slate-600 rounded-[8px] flex items-center justify-between opacity-60">
                        <span className="text-slate-400 font-sans text-sm">Tambahkan file ({field.accepted_types})</span>
                        <UploadCloud className="text-blue-500 w-5 h-5" />
                    </div>
                )}
            </div>
        );
    };

    if (loading) return <div className="p-10 text-center text-slate-500">Memuat...</div>;
    if (!product) return <div className="p-10 text-center text-red-500">Produk tidak ditemukan</div>;

    return (
        <div className="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-4">
            {/* Header Sticky */}
            <div className="sticky top-0 z-30 bg-white dark:bg-slate-800 border border-semudah-primary/10 shadow-[0_4px_12px_rgba(0,0,0,0.03)] px-6 py-4 flex justify-between items-center max-w-5xl mx-auto rounded-[12px] mb-8 font-sans">
                <div className="flex items-center gap-4">
                    <button onClick={() => navigate('/admin/products')} className="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white bg-slate-100 dark:bg-slate-700 rounded-[8px] transition-colors">
                        <ArrowLeft size={20} />
                    </button>
                    <div>
                        <h2 className="text-lg font-heading font-bold text-slate-800 dark:text-white line-clamp-1">{product.name}</h2>
                        <p className="text-slate-400 font-sans text-xs">Form Builder WYSIWYG</p>
                    </div>
                </div>
                <div className="flex items-center gap-3">
                    <div className="relative">
                        <button onClick={() => setShowTemplateMenu(!showTemplateMenu)} className="text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 px-4 py-2 rounded-[8px] font-sans text-sm font-bold flex items-center gap-2 transition-colors">
                            <List size={16} /> Template
                        </button>
                        {showTemplateMenu && (
                            <div className="absolute top-full right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-[12px] shadow-xl border border-semudah-primary/10 overflow-hidden z-50">
                                {[
                                    { label: 'Jasa Print', slug: 'jasa-print' },
                                    { label: 'Jasa Fotocopy', slug: 'jasa-fotocopy' },
                                    { label: 'Custom Mug', slug: 'custom-mug' },
                                    { label: 'Custom Kaos', slug: 'custom-kaos' },
                                    { label: 'Custom Totebag', slug: 'custom-tote-bag' },
                                    { label: 'Custom E-Money', slug: 'custom-emoney' },
                                    { label: 'Gantungan Kunci', slug: 'custom-gantungan-kunci' },
                                ].map(tpl => (
                                    <button key={tpl.slug} onClick={() => { loadTemplate(tpl.slug); setShowTemplateMenu(false); }} className="w-full text-left px-4 py-2.5 text-sm font-sans hover:bg-semudah-secondary/10 dark:hover:bg-slate-700 hover:text-semudah-primary dark:hover:text-white border-b border-semudah-primary/10 dark:border-slate-700 last:border-0">
                                        {tpl.label}
                                    </button>
                                ))}
                            </div>
                        )}
                    </div>
                    <button onClick={handleSave} disabled={saving} className="bg-semudah-primary hover:bg-semudah-primary/90 text-white px-5 py-2 rounded-[8px] font-sans font-bold flex items-center gap-2 transition-colors shadow-sm disabled:opacity-50 text-sm">
                        <Save size={16} /> {saving ? 'Menyimpan...' : 'Simpan'}
                    </button>
                </div>
            </div>

            {/* Main Canvas */}
            <div className="max-w-3xl mx-auto px-4 relative flex gap-6">
                
                <div className="flex-1 space-y-4">
                    {/* Form Header */}
                    <div className="bg-white dark:bg-slate-800 rounded-[12px] shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-semudah-primary/10 border-t-[8px] border-t-semudah-primary p-8 mb-6 relative group overflow-hidden">
                        <h1 className="text-3xl font-heading font-bold text-slate-800 dark:text-white mb-2 outline-none">{product.name}</h1>
                        <p className="text-slate-500 font-sans dark:text-slate-400 outline-none">{product.description || 'Deskripsi formulir pesanan produk ini.'}</p>
                    </div>

                    {/* Form Fields */}
                    {schema.length === 0 && (
                        <div className="text-center py-16 bg-white dark:bg-slate-800 rounded-[12px] border border-dashed border-gray-300 dark:border-slate-600">
                            <List className="w-12 h-12 text-slate-300 mx-auto mb-3" />
                            <h3 className="text-lg font-heading font-bold text-slate-700 dark:text-white">Form Masih Kosong</h3>
                            <p className="text-slate-500 font-sans text-sm">Gunakan tombol + di sebelah kanan untuk menambahkan pertanyaan.</p>
                        </div>
                    )}
                    
                    {schema.map((field, index) => {
                        const isActive = activeFieldId === field.id;
                        
                        if (!isActive) {
                            return (
                                <div key={field.id} onClick={() => setActiveFieldId(field.id)} className="bg-white dark:bg-slate-800 p-6 rounded-[12px] shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-transparent hover:border-gray-200 dark:hover:border-slate-700 cursor-pointer relative group transition-all">
                                    <div className="absolute top-2 left-0 right-0 flex justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div className="w-6 h-1 bg-gray-300 dark:bg-slate-600 rounded-full"></div>
                                    </div>
                                    {renderFieldPreview(field)}
                                </div>
                            );
                        }

                        // ACTIVE FIELD (EDIT MODE)
                        return (
                            <div key={field.id} className="bg-white dark:bg-slate-800 p-6 rounded-[12px] shadow-[0_8px_24px_rgba(0,0,0,0.05)] border-l-[4px] border-semudah-primary relative transition-all z-10 my-6 transform scale-[1.02]">
                                
                                <div className="flex flex-col md:flex-row gap-4 mb-4">
                                    {/* Label Editor */}
                                    <div className="flex-1">
                                        <input type="text" value={field.label} onChange={(e) => updateField(index, { label: e.target.value })} 
                                            className="w-full text-lg bg-gray-50 dark:bg-slate-900 border-b-2 border-gray-300 focus:border-semudah-primary dark:border-slate-600 px-3 py-2 font-sans font-bold text-slate-800 dark:text-white outline-none transition-colors" placeholder="Pertanyaan / Label" autoFocus />
                                        {field.type === 'header' && (
                                            <input type="text" value={field.description || ''} onChange={(e) => updateField(index, { description: e.target.value })} 
                                                className="w-full mt-2 text-sm bg-gray-50 dark:bg-slate-900 border-b border-gray-300 focus:border-semudah-primary dark:border-slate-600 px-3 py-1.5 text-slate-600 dark:text-slate-400 outline-none transition-colors" placeholder="Deskripsi opsional..." />
                                        )}
                                    </div>
                                    {/* Type Selector Display */}
                                    <div className="bg-slate-100 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 px-4 py-2 rounded-lg flex items-center justify-between md:w-48 text-sm font-semibold text-slate-700 dark:text-slate-300 h-10">
                                        <span>{fieldTypes.find(f => f.type === field.type)?.label || field.type}</span>
                                    </div>
                                </div>

                                {/* Options Editor (Radio, Checkbox, Select) */}
                                {['radio', 'checkbox', 'select'].includes(field.type) && (
                                    <div className="mt-4 space-y-2 mb-6">
                                        {field.options?.map((opt, optIdx) => (
                                            <div key={optIdx} className="flex items-center gap-3 group">
                                                {field.type === 'radio' && <CircleDot className="w-5 h-5 text-gray-300" />}
                                                {field.type === 'checkbox' && <CheckSquare className="w-5 h-5 text-gray-300" />}
                                                {field.type === 'select' && <span className="text-gray-400 font-bold w-5 text-center">{optIdx+1}.</span>}
                                                
                                                <input type="text" value={opt.label} onChange={(e) => {
                                                    const newOpts = [...field.options];
                                                    newOpts[optIdx].label = e.target.value;
                                                    newOpts[optIdx].value = e.target.value;
                                                    updateField(index, { options: newOpts });
                                                }} className="flex-1 max-w-[200px] border-b border-transparent focus:border-semudah-primary bg-transparent hover:border-gray-300 outline-none py-1 text-slate-700 dark:text-slate-200" placeholder="Opsi" />
                                                
                                                <div className="flex items-center gap-1 opacity-50 focus-within:opacity-100 hover:opacity-100 transition-opacity">
                                                    <span className="text-xs font-semibold text-gray-500">+Rp</span>
                                                    <input type="number" value={opt.price_modifier || ''} onChange={(e) => {
                                                        const newOpts = [...field.options];
                                                        newOpts[optIdx].price_modifier = Number(e.target.value) || 0;
                                                        updateField(index, { options: newOpts });
                                                    }} className="w-20 border-b border-gray-300 focus:border-semudah-primary bg-transparent outline-none py-1 text-slate-700 dark:text-slate-200 text-sm" placeholder="Harga..." />
                                                </div>

                                                {field.options.length > 1 && (
                                                    <button onClick={() => {
                                                        const newOpts = [...field.options];
                                                        newOpts.splice(optIdx, 1);
                                                        updateField(index, { options: newOpts });
                                                    }} className="p-1 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <X size={16} />
                                                    </button>
                                                )}
                                            </div>
                                        ))}
                                        <div className="flex items-center gap-3 mt-2">
                                            {field.type === 'radio' && <CircleDot className="w-5 h-5 text-gray-300" />}
                                            {field.type === 'checkbox' && <CheckSquare className="w-5 h-5 text-gray-300" />}
                                            {field.type === 'select' && <span className="text-gray-400 font-bold w-5 text-center">{field.options?.length + 1}.</span>}
                                            <button onClick={() => {
                                                const newOpts = [...(field.options || []), { label: `Opsi ${field.options?.length + 1 || 1}`, value: `Opsi ${field.options?.length + 1 || 1}`, price_modifier: 0 }];
                                                updateField(index, { options: newOpts });
                                            }} className="text-sm font-semibold text-gray-500 hover:text-semudah-primary transition-colors border-b border-transparent py-1">
                                                Tambahkan opsi
                                            </button>
                                        </div>
                                    </div>
                                )}

                                {/* Specific Settings (Number min, File accept) */}
                                {(field.type === 'number' || field.type === 'file' || field.type !== 'header') && (
                                    <div className="mt-4 mb-6 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl flex flex-wrap gap-4 items-end">
                                        <div className="w-full">
                                            <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Variabel (Internal ID)</label>
                                            <input type="text" value={field.name} onChange={(e) => updateField(index, { name: e.target.value })} className="w-full max-w-[200px] bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-1.5 text-sm font-mono text-slate-600 dark:text-slate-400 outline-none focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary" />
                                        </div>
                                        {field.type === 'number' && (
                                            <div>
                                                <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nilai Minimum</label>
                                                <input type="number" value={field.min ?? ''} onChange={(e) => updateField(index, { min: e.target.value })} className="w-32 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-1.5 text-sm outline-none focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary" />
                                            </div>
                                        )}
                                        {field.type === 'file' && (
                                            <div>
                                                <label className="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ekstensi Diterima</label>
                                                <input type="text" value={field.accepted_types ?? ''} onChange={(e) => updateField(index, { accepted_types: e.target.value })} className="w-48 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-1.5 text-sm outline-none focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary placeholder-slate-400" placeholder=".png,.jpg,.pdf" />
                                            </div>
                                        )}
                                    </div>
                                )}

                                {/* Bottom Toolbar */}
                                <div className="border-t border-gray-100 dark:border-slate-700 pt-4 flex flex-wrap justify-between items-center gap-4 mt-6">
                                    <div className="flex items-center gap-1">
                                        <button onClick={() => moveField(index, 'up')} disabled={index === 0} className="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg disabled:opacity-30 tooltip-trigger" title="Naikkan">
                                            <ArrowUp size={18} />
                                        </button>
                                        <button onClick={() => moveField(index, 'down')} disabled={index === schema.length - 1} className="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg disabled:opacity-30 tooltip-trigger" title="Turunkan">
                                            <ArrowDown size={18} />
                                        </button>
                                        <div className="w-px h-6 bg-gray-200 dark:bg-slate-700 mx-2"></div>
                                        <button onClick={() => cloneField(index)} className="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg" title="Duplikat">
                                            <Copy size={18} />
                                        </button>
                                        <button onClick={() => removeField(index)} className="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg" title="Hapus">
                                            <Trash2 size={18} />
                                        </button>
                                    </div>

                                    {field.type !== 'header' && (
                                        <div className="flex items-center gap-4 border-l border-gray-200 dark:border-slate-700 pl-4">
                                            <label className="flex items-center cursor-pointer">
                                                <div className="relative">
                                                    <input type="checkbox" className="sr-only" checked={field.required} onChange={(e) => updateField(index, { required: e.target.checked })} />
                                                    <div className={`block w-10 h-6 rounded-full transition-colors ${field.required ? 'bg-semudah-primary' : 'bg-gray-300 dark:bg-slate-600'}`}></div>
                                                    <div className={`absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform ${field.required ? 'transform translate-x-4' : ''}`}></div>
                                                </div>
                                                <span className="ml-3 text-sm font-semibold text-slate-600 dark:text-slate-300">Wajib Diisi</span>
                                            </label>
                                        </div>
                                    )}
                                </div>
                                
                            </div>
                        );
                    })}
                </div>

                {/* Floating Add Menu (Sidebar Style) */}
                <div className="w-16 hidden md:flex flex-col gap-2 sticky top-32 h-fit bg-white dark:bg-slate-800 p-2 rounded-[12px] shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-semudah-primary/10 items-center py-4">
                    <button onClick={() => setShowAddMenu(!showAddMenu)} className="w-10 h-10 bg-semudah-primary hover:bg-semudah-primary/90 text-white rounded-full flex items-center justify-center transition-transform hover:scale-105 mb-2 shadow-sm">
                        <Plus size={20} />
                    </button>
                    {showAddMenu && fieldTypes.map((fType) => (
                        <button key={fType.type} onClick={() => handleAddField(fType.default)} className="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-semudah-primary dark:hover:text-semudah-accent rounded-full transition-colors relative group">
                            <fType.icon size={20} />
                            {/* Tooltip */}
                            <div className="absolute left-full ml-3 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap z-50">
                                {fType.label}
                            </div>
                        </button>
                    ))}
                </div>

                {/* Mobile Floating Add Menu */}
                <div className="fixed bottom-8 right-8 z-50 flex flex-col items-end gap-3 md:hidden">
                    {showAddMenu && (
                        <div className="bg-white dark:bg-slate-800 rounded-[12px] shadow-2xl border border-semudah-primary/10 p-2 w-64 grid grid-cols-2 gap-1 animate-in fade-in slide-in-from-bottom-5">
                            {fieldTypes.map((fType) => (
                                <button key={fType.type} onClick={() => handleAddField(fType.default)} className="flex flex-col items-center justify-center p-3 rounded-[8px] hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors gap-2 group">
                                    <div className="text-slate-500 group-hover:text-semudah-primary transition-colors">
                                        <fType.icon size={20} />
                                    </div>
                                    <span className="text-[10px] font-bold text-slate-600 dark:text-slate-300 text-center leading-tight">{fType.label}</span>
                                </button>
                            ))}
                        </div>
                    )}
                    <button onClick={() => setShowAddMenu(!showAddMenu)} className="w-14 h-14 bg-semudah-primary hover:bg-semudah-primary/90 text-white rounded-full shadow-lg shadow-semudah-primary/30 flex items-center justify-center transition-transform hover:scale-105">
                        {showAddMenu ? <X size={24} /> : <Plus size={24} />}
                    </button>
                </div>

            </div>
            
            {/* Overlay to close active field */}
            {activeFieldId && (
                <div className="fixed inset-0 z-0 bg-transparent" onClick={() => setActiveFieldId(null)}></div>
            )}
        </div>
    );
};

export default ProductFormBuilder;
