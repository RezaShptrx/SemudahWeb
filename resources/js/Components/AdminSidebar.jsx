import React from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { LayoutDashboard, ShoppingCart, Users, Tag, Package, LogOut, X, Settings, List, UserCheck, Archive } from 'lucide-react';
import axios from 'axios';

const AdminSidebar = ({ sidebarOpen, setSidebarOpen, user }) => {
    const location = useLocation();
    const navigate = useNavigate();
    const isAdmin = user?.role === 'admin';

    const handleLogout = async () => {
        try {
            const token = localStorage.getItem('token');
            if (token) {
                await axios.post('/api/auth/logout', {}, {
                    headers: { Authorization: `Bearer ${token}` }
                });
            }
        } catch (e) {
            console.error(e);
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            navigate('/admin/login');
        }
    };

    const navItems = [
        { name: 'Dashboard', path: '/admin/dashboard', icon: LayoutDashboard },
        { name: 'Orders', path: '/admin/orders', icon: ShoppingCart },
        { name: 'Products', path: '/admin/products', icon: Package, adminOnly: true },
        { name: 'Categories', path: '/admin/categories', icon: List, adminOnly: true },
        { name: 'Services', path: '/admin/services', icon: Settings, adminOnly: true },
        { name: 'Users', path: '/admin/users', icon: Users, adminOnly: true },
        { name: 'Promo Codes', path: '/admin/promo-codes', icon: Tag, adminOnly: true },
        { name: 'Log Absensi', path: '/admin/attendances', icon: UserCheck },
        { name: 'Arsip Harian', path: '/admin/archives', icon: Archive, adminOnly: true },
        { name: 'Pengaturan', path: '/admin/settings', icon: Settings, adminOnly: true },
    ].filter(item => !item.adminOnly || isAdmin);

    return (
        <>
            {/* Mobile Sidebar Overlay */}
            {sidebarOpen && (
                <div 
                    className="fixed inset-0 bg-slate-900/50 z-20 lg:hidden"
                    onClick={() => setSidebarOpen(false)}
                />
            )}

            {/* Sidebar */}
            <aside 
                className={`
                    fixed lg:static inset-y-0 left-0 z-30 w-64 bg-semudah-bg dark:bg-slate-900 border-r border-gray-100 dark:border-slate-800 
                    transform transition-transform duration-300 ease-in-out lg:transform-none flex flex-col font-sans
                    ${sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
                `}
            >
                <div className="flex items-center justify-between h-16 px-6 border-b border-gray-100 dark:border-slate-800">
                    <span className="text-xl font-heading font-bold text-semudah-primary dark:text-semudah-accent">
                        SEMUDAH
                    </span>
                    <button onClick={() => setSidebarOpen(false)} className="lg:hidden text-slate-500 hover:text-semudah-secondary">
                        <X size={20} />
                    </button>
                </div>

                <div className="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    {navItems.map((item) => {
                        const Icon = item.icon;
                        const isActive = location.pathname.startsWith(item.path);
                        return (
                            <Link 
                                key={item.name} 
                                to={item.path}
                                className={`flex items-center px-3 py-2.5 rounded-[8px] transition-all duration-200 ${
                                    isActive 
                                    ? 'bg-semudah-primary text-white shadow-sm dark:bg-semudah-secondary dark:text-slate-900' 
                                    : 'text-slate-600 dark:text-slate-400 hover:bg-semudah-secondary/10 dark:hover:bg-slate-800 hover:text-semudah-secondary dark:hover:text-semudah-accent'
                                }`}
                            >
                                <Icon size={18} className="mr-3" />
                                <span className="font-medium">{item.name}</span>
                            </Link>
                        );
                    })}
                </div>

                <div className="p-4 border-t border-gray-100 dark:border-slate-800">
                    <button 
                        onClick={handleLogout}
                        className="flex w-full items-center px-3 py-2.5 rounded-[8px] text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                    >
                        <LogOut size={18} className="mr-3" />
                        <span className="font-medium font-sans">Logout</span>
                    </button>
                </div>
            </aside>
        </>
    );
};

export default AdminSidebar;
