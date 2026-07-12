import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Calendar, DollarSign, Users, ChevronDown, ChevronUp } from 'lucide-react';

const DailyArchives = () => {
    const [archives, setArchives] = useState([]);
    const [loading, setLoading] = useState(true);
    const [expandedRow, setExpandedRow] = useState(null);

    useEffect(() => {
        fetchArchives();
    }, []);

    const fetchArchives = async () => {
        try {
            const token = localStorage.getItem('token');
            const res = await axios.get('/api/reports/archives', {
                headers: { Authorization: `Bearer ${token}` }
            });
            setArchives(res.data);
        } catch (err) {
            console.error('Error fetching archives:', err);
        } finally {
            setLoading(false);
        }
    };

    const toggleRow = (date) => {
        if (expandedRow === date) {
            setExpandedRow(null);
        } else {
            setExpandedRow(date);
        }
    };

    const formatTime = (dateString) => {
        if (!dateString) return '-';
        return new Date(dateString).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    };

    if (loading) return <div className="text-center py-10">Memuat data arsip...</div>;

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <Calendar className="text-cyan-500" /> Arsip Harian
                </h2>
                <p className="text-slate-500 text-sm mt-1">Rekapitulasi penjualan dan absensi petugas per hari.</p>
            </div>

            <div className="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead className="bg-gray-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 uppercase text-xs font-semibold">
                            <tr>
                                <th className="px-6 py-4">Tanggal</th>
                                <th className="px-6 py-4">Total Order (Lunas)</th>
                                <th className="px-6 py-4">Pemasukan</th>
                                <th className="px-6 py-4">Kehadiran Petugas</th>
                                <th className="px-6 py-4 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100 dark:divide-slate-700">
                            {archives.map(arch => (
                                <React.Fragment key={arch.date}>
                                    <tr 
                                        className="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer"
                                        onClick={() => toggleRow(arch.date)}
                                    >
                                        <td className="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                            {arch.formatted_date}
                                        </td>
                                        <td className="px-6 py-4 font-medium">
                                            {arch.total_orders} Pesanan
                                        </td>
                                        <td className="px-6 py-4 font-bold text-cyan-600 dark:text-cyan-400">
                                            Rp {parseInt(arch.total_revenue).toLocaleString()}
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2">
                                                <Users size={16} className="text-indigo-500" /> 
                                                <span>{arch.attendances.length} Orang</span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <button className="text-slate-400 hover:text-cyan-500">
                                                {expandedRow === arch.date ? <ChevronUp size={20} /> : <ChevronDown size={20} />}
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    {/* Expanded Detail Row */}
                                    {expandedRow === arch.date && (
                                        <tr className="bg-slate-50 dark:bg-slate-800/80">
                                            <td colSpan="5" className="px-6 py-4">
                                                <div className="pl-4 border-l-2 border-cyan-500 my-2">
                                                    <h4 className="font-semibold text-slate-800 dark:text-slate-200 mb-3 text-sm">Daftar Petugas Piket:</h4>
                                                    {arch.attendances.length > 0 ? (
                                                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                                            {arch.attendances.map((att, idx) => (
                                                                <div key={idx} className="bg-white dark:bg-slate-700 p-3 rounded-xl border border-gray-100 dark:border-slate-600 shadow-sm flex flex-col">
                                                                    <span className="font-bold text-slate-800 dark:text-white">{att.name}</span>
                                                                    <span className="text-xs text-slate-500 mb-2">{att.major}</span>
                                                                    <div className="flex items-center justify-between text-xs mt-auto font-medium">
                                                                        <span className="text-emerald-600 bg-emerald-50 px-2 py-1 rounded dark:bg-emerald-900/30 dark:text-emerald-400">In: {formatTime(att.check_in)}</span>
                                                                        <span className="text-slate-600 bg-slate-100 px-2 py-1 rounded dark:bg-slate-600 dark:text-slate-300">Out: {formatTime(att.check_out)}</span>
                                                                    </div>
                                                                </div>
                                                            ))}
                                                        </div>
                                                    ) : (
                                                        <p className="text-sm text-slate-500 italic">Tidak ada petugas yang tercatat hadir pada hari ini.</p>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    )}
                                </React.Fragment>
                            ))}
                            {archives.length === 0 && (
                                <tr><td colSpan="5" className="px-6 py-8 text-center text-slate-500">Belum ada arsip data yang tercatat.</td></tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default DailyArchives;
