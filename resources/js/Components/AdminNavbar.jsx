import React from 'react';
import { useLocation } from 'react-router-dom';
import { Menu, Sun, Moon } from 'lucide-react';

const AdminNavbar = ({ sidebarOpen, setSidebarOpen, user, isDark, toggleDarkMode }) => {
    const location = useLocation();

    return (
        <header className="h-16 bg-semudah-bg dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 z-10 shadow-[0_4px_20px_rgba(0,0,0,0.04)] dark:shadow-slate-950/50">
            <div className="flex items-center">
                <button 
                    onClick={() => setSidebarOpen(true)}
                    className="lg:hidden text-slate-500 hover:text-semudah-secondary dark:hover:text-semudah-accent mr-4 transition-colors"
                >
                    <Menu size={24} />
                </button>
                <h1 className="text-xl font-heading font-bold text-slate-800 dark:text-white capitalize">
                    {location.pathname.split('/').pop().replace('-', ' ') || 'Dashboard'}
                </h1>
            </div>
            <div className="flex items-center gap-4">
                <button 
                    onClick={toggleDarkMode}
                    className="p-2 rounded-full text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-semudah-secondary dark:hover:text-semudah-accent transition-colors"
                >
                    {isDark ? <Sun size={20} /> : <Moon size={20} />}
                </button>
                <div className="h-6 w-px bg-gray-200 dark:bg-slate-800 hidden sm:block"></div>
                <div className="text-right hidden sm:block font-sans">
                    <div className="text-sm font-bold text-slate-800 dark:text-white leading-none">{user?.name || 'User'}</div>
                    <div className="text-xs text-slate-500 capitalize">{user?.role || 'Guest'}</div>
                </div>
                <div className="w-8 h-8 rounded-full bg-semudah-secondary/10 dark:bg-slate-800 flex items-center justify-center text-semudah-primary dark:text-semudah-accent font-bold border border-semudah-secondary/20 dark:border-slate-700">
                    {user?.name ? user.name.charAt(0).toUpperCase() : 'U'}
                </div>
            </div>
        </header>
    );
};

export default AdminNavbar;
