import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Clock, LogIn, LogOut, CheckCircle, UserCheck } from 'lucide-react';

const Attendances = () => {
    const [attendances, setAttendances] = useState([]);
    const [loading, setLoading] = useState(true);
    const [modalOpen, setModalOpen] = useState(false);
    
    // Check if user is Admin
    const rawUser = localStorage.getItem('user');
    const user = rawUser ? JSON.parse(rawUser) : null;
    const isAdmin = user?.role === 'admin';

    // Check-in Form State
    const [formData, setFormData] = useState({ name: '', class: '', major: 'RPL' });

    useEffect(() => {
        fetchAttendances();
    }, []);

    const fetchAttendances = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get('/api/attendances', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setAttendances(res.data);
        } catch (err) {
            console.error('Error fetching attendances:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleCheckIn = async (e) => {
        e.preventDefault();
        try {
            const token = localStorage.getItem('token');
            await axios.post('/api/attendances', formData, {
                headers: { Authorization: `Bearer ${token}` }
            });
            setFormData({ name: '', class: '', major: 'RPL' });
            setModalOpen(false);
            fetchAttendances();
        } catch (err) {
            alert('Gagal melakukan check-in. Pastikan semua field terisi.');
            console.error(err);
        }
    };

    const handleCheckOut = async (id) => {
        if (!confirm('Anda yakin ingin Check-out sekarang?')) return;
        try {
            const token = localStorage.getItem('token');
            await axios.put(`/api/attendances/${id}`, { check_out: true }, {
                headers: { Authorization: `Bearer ${token}` }
            });
            fetchAttendances();
        } catch (err) {
            alert('Gagal melakukan check-out.');
            console.error(err);
        }
    };

    const formatTime = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    };

    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    };

    if (loading) return <div className="text-center py-10">Memuat data absensi...</div>;

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h2 className="text-2xl font-heading font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <UserCheck className="text-semudah-primary" /> {isAdmin ? 'Log Absensi Petugas' : 'Absensi Penjaga'}
                    </h2>
                    <p className="text-slate-500 font-sans text-sm mt-1">Catat jam masuk dan keluar petugas (Siswa Praktik)</p>
                </div>
                {!isAdmin && (
                    <button 
                        onClick={() => setModalOpen(true)}
                        className="flex items-center gap-2 bg-semudah-primary hover:bg-semudah-primary/90 text-white px-5 py-2.5 rounded-[8px] shadow-sm transition-transform hover:-translate-y-0.5 font-sans font-bold"
                    >
                        <LogIn size={18} /> Check-In Sekarang
                    </button>
                )}
            </div>

            <div className="bg-white dark:bg-slate-800 rounded-[12px] shadow-[0_4px_12px_rgba(0,0,0,0.03)] border border-semudah-primary/10 dark:border-slate-700 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300 font-sans">
                        <thead className="bg-gray-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 uppercase text-xs font-semibold">
                            <tr>
                                <th className="px-6 py-4">Tanggal</th>
                                <th className="px-6 py-4">Nama Petugas</th>
                                <th className="px-6 py-4">Kelas & Jurusan</th>
                                <th className="px-6 py-4">Jam Masuk</th>
                                <th className="px-6 py-4">Jam Keluar</th>
                                <th className="px-6 py-4 text-right">Status / Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 dark:divide-slate-700">
                            {attendances.map(att => (
                                <tr key={att.id} className="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td className="px-6 py-4 whitespace-nowrap font-sans text-slate-800 dark:text-slate-200">
                                        {formatDate(att.created_at)}
                                    </td>
                                    <td className="px-6 py-4 font-heading font-bold text-semudah-primary">
                                        {att.name}
                                    </td>
                                    <td className="px-6 py-4">
                                        {att.class} - {att.major}
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium bg-emerald-50 dark:bg-emerald-900/30 w-max px-2.5 py-1 rounded-[4px]">
                                            <LogIn size={14} /> {formatTime(att.check_in)}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        {att.check_out ? (
                                            <div className="flex items-center gap-1 text-slate-600 dark:text-slate-400 font-medium bg-slate-100 dark:bg-slate-700 w-max px-2.5 py-1 rounded-[4px]">
                                                <LogOut size={14} /> {formatTime(att.check_out)}
                                            </div>
                                        ) : (
                                            <span className="text-xs text-yellow-600 dark:text-yellow-400 font-medium px-2.5 py-1 bg-yellow-50 dark:bg-yellow-900/30 rounded-[4px]">Belum Checkout</span>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        {!att.check_out ? (
                                            <button onClick={() => handleCheckOut(att.id)} className="text-xs flex items-center gap-1 ml-auto bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 px-3 py-1.5 rounded-[4px] transition-colors font-sans font-bold">
                                                <LogOut size={14} /> Check-out
                                            </button>
                                        ) : (
                                            <span className="text-xs flex items-center justify-end gap-1 text-green-500 font-sans font-bold">
                                                <CheckCircle size={14} /> Selesai Bertugas
                                            </span>
                                        )}
                                    </td>
                                </tr>
                            ))}
                            {attendances.length === 0 && (
                                <tr><td colSpan="6" className="px-6 py-8 text-center text-slate-500">Belum ada data absensi.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal Check-In */}
            {modalOpen && (
                <div className="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div className="bg-white dark:bg-slate-800 rounded-[12px] border border-semudah-primary/10 font-sans w-full max-w-md shadow-xl overflow-hidden transform transition-all">
                        <div className="bg-semudah-primary p-6 text-white">
                            <h3 className="text-2xl font-heading font-bold flex items-center gap-2">
                                <Clock className="opacity-80" /> Form Check-In
                            </h3>
                            <p className="text-cyan-50 text-sm mt-1 opacity-90 font-sans">Silakan isi data diri sebelum mulai bertugas.</p>
                        </div>
                        
                        <form onSubmit={handleCheckIn} className="p-6 space-y-4">
                            <div>
                                <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Nama Lengkap</label>
                                <input type="text" value={formData.name} onChange={(e) => setFormData({...formData, name: e.target.value})} required placeholder="Misal: Budi Santoso"
                                    className="w-full px-4 py-3 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none transition-all" />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Kelas</label>
                                    <input type="text" value={formData.class} onChange={(e) => setFormData({...formData, class: e.target.value})} required placeholder="Misal: XI"
                                        className="w-full px-4 py-3 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none transition-all" />
                                </div>
                                <div>
                                    <label className="block text-sm font-semibold text-semudah-primary dark:text-slate-300 mb-1">Jurusan</label>
                                    <input type="text" value={formData.major} onChange={(e) => setFormData({...formData, major: e.target.value})} required placeholder="Misal: DKV 1"
                                        className="w-full px-4 py-3 rounded-[8px] border border-semudah-secondary/40 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:ring-1 focus:ring-semudah-primary focus:border-semudah-primary outline-none transition-all" />
                                </div>
                            </div>
                            
                            <div className="pt-4 flex justify-end gap-3 border-t border-semudah-primary/10 dark:border-slate-700 mt-6 pt-6">
                                <button type="button" onClick={() => setModalOpen(false)} className="px-5 py-2.5 rounded-[8px] text-slate-600 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 font-bold transition">
                                    Batal
                                </button>
                                <button type="submit" className="px-6 py-2.5 rounded-[8px] text-white bg-semudah-primary hover:bg-semudah-primary/90 font-bold transition-transform hover:-translate-y-0.5 shadow-sm shadow-semudah-primary/30 flex items-center gap-2">
                                    <LogIn size={18} /> Masuk Shift
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
};

export default Attendances;
