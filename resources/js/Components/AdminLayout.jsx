import React, { useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { LayoutDashboard, ShoppingCart, Users, Tag, Package, LogOut, Menu, X, Settings, List, UserCheck, Archive, Layout, Sun, Moon } from 'lucide-react';
import axios from 'axios';

const AdminLayout = ({ children }) => {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [isDark, setIsDark] = useState(document.documentElement.classList.contains('dark'));
    const location = useLocation();
    const navigate = useNavigate();

    const toggleDarkMode = () => {
        const nextDark = !isDark;
        setIsDark(nextDark);
        localStorage.setItem('dark', nextDark);
        document.documentElement.classList.toggle('dark', nextDark);
    };

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

    const rawUser = localStorage.getItem('user');
    const user = rawUser ? JSON.parse(rawUser) : null;
    const isAdmin = user?.role === 'admin';

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
    ].filter(item => !item.adminOnly || isAdmin);

    return (
        <div className="flex h-screen bg-gray-50 dark:bg-slate-900 overflow-hidden font-sans">
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
                    fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white dark:bg-slate-800 border-r border-gray-100 dark:border-slate-700 
                    transform transition-transform duration-300 ease-in-out lg:transform-none flex flex-col
                    ${sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
                `}
            >
                <div className="flex items-center justify-between h-16 px-6 border-b border-gray-100 dark:border-slate-700">
                    <span className="text-xl font-bold bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent">
                        SEMUDAH
                    </span>
                    <button onClick={() => setSidebarOpen(false)} className="lg:hidden text-slate-500">
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
                                className={`flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 ${
                                    isActive 
                                    ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-md shadow-cyan-500/20' 
                                    : 'text-slate-600 dark:text-slate-400 hover:bg-cyan-50 dark:hover:bg-slate-700/50 hover:text-cyan-600 dark:hover:text-cyan-400'
                                }`}
                            >
                                <Icon size={18} className="mr-3" />
                                <span className="font-medium">{item.name}</span>
                            </Link>
                        );
                    })}
                </div>

                <div className="p-4 border-t border-gray-100 dark:border-slate-700">
                    <button 
                        onClick={handleLogout}
                        className="flex w-full items-center px-3 py-2.5 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                    >
                        <LogOut size={18} className="mr-3" />
                        <span className="font-medium">Logout</span>
                    </button>
                </div>
            </aside>

            {/* Main Content */}
            <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
                {/* Header */}
                <header className="h-16 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between px-4 sm:px-6 z-10 shadow-sm">
                    <div className="flex items-center">
                        <button 
                            onClick={() => setSidebarOpen(true)}
                            className="lg:hidden text-slate-500 hover:text-cyan-500 mr-4"
                        >
                            <Menu size={24} />
                        </button>
                        <h1 className="text-xl font-bold text-slate-800 dark:text-white capitalize">
                            {location.pathname.split('/').pop().replace('-', ' ') || 'Dashboard'}
                        </h1>
                    </div>
                    <div className="flex items-center gap-4">
                        <button 
                            onClick={toggleDarkMode}
                            className="p-2 rounded-full text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-cyan-500 dark:hover:text-cyan-400 transition-colors"
                        >
                            {isDark ? <Sun size={20} /> : <Moon size={20} />}
                        </button>
                        <div className="h-6 w-px bg-gray-200 dark:bg-slate-700 hidden sm:block"></div>
                        <div className="text-right hidden sm:block">
                            <div className="text-sm font-bold text-slate-800 dark:text-white leading-none">{user?.name || 'User'}</div>
                            <div className="text-xs text-slate-500 capitalize">{user?.role || 'Guest'}</div>
                        </div>
                        <div className="w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-900 flex items-center justify-center text-cyan-600 dark:text-cyan-300 font-bold border border-cyan-200 dark:border-cyan-700">
                            {user?.name ? user.name.charAt(0).toUpperCase() : 'U'}
                        </div>
                    </div>
                </header>

                {/* Page Content */}
                <main className="flex-1 overflow-auto p-4 sm:p-6 lg:p-8">
                    {children}
                </main>
            </div>
        </div>
    );
};

export default AdminLayout;
