import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import { Eye, EyeOff, AlertCircle, ArrowRight } from 'lucide-react';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError('');

        try {
            const res = await axios.post('/api/auth/login', { email, password });
            const token = res.data.access_token;
            const user = res.data.user;
            
            // Store token and user in localStorage
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            
            // Redirect to dashboard
            navigate('/admin/dashboard');
        } catch (err) {
            setError(err.response?.data?.error || 'Email atau password yang Anda masukkan salah. Silakan coba lagi.');
            console.error('Login error:', err);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="bg-semudah-bg dark:bg-slate-900 text-slate-900 dark:text-white font-sans h-screen w-screen overflow-hidden flex flex-col md:flex-row">
            
            {/* Left Panel (Brand/Atmosphere) */}
            <div className="hidden md:flex md:w-[40%] bg-semudah-anchor relative flex-col justify-between p-8 lg:p-12 overflow-hidden">
                {/* Decorative Shapes */}
                <div aria-hidden="true" className="absolute top-[-20%] left-[-10%] w-[120%] h-[120%] z-0 pointer-events-none opacity-20">
                    <svg className="w-full h-full fill-semudah-primary" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <path d="M0,0 L100,0 L50,100 Z"></path>
                    </svg>
                </div>
                <div aria-hidden="true" className="absolute bottom-[-10%] right-[-20%] w-[80%] h-[80%] z-0 pointer-events-none opacity-30">
                    <svg className="w-full h-full fill-semudah-primary" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="50"></circle>
                    </svg>
                </div>
                
                <div className="relative z-10 flex-grow flex flex-col justify-center">
                    <h1 className="font-heading text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight">SEMUDAH</h1>
                    <p className="font-sans text-lg lg:text-xl text-white opacity-90 max-w-sm leading-relaxed">
                        Kelola pesanan dan layanan dengan mudah.
                    </p>
                </div>
                
                <div className="relative z-10 text-white opacity-70 font-sans text-sm">
                    © {new Date().getFullYear()} SEMUDAH Printing & Merchandise
                </div>
            </div>

            {/* Right Panel (Login Form) */}
            <div className="w-full md:w-[60%] bg-white dark:bg-slate-900 flex flex-col justify-center p-8 md:px-[80px] lg:px-[120px] xl:px-[180px] h-full overflow-y-auto">
                
                {/* Mobile Header (Visible only on mobile) */}
                <div className="md:hidden mb-12 text-center">
                    <h1 className="font-heading text-3xl font-bold text-semudah-primary dark:text-semudah-accent tracking-tight">SEMUDAH</h1>
                </div>
                
                <div className="w-full max-w-md mx-auto">
                    <div className="mb-10 text-center md:text-left">
                        <h2 className="font-heading text-2xl lg:text-3xl font-bold text-semudah-primary dark:text-semudah-accent mb-2">Masuk ke Panel Admin</h2>
                        <p className="font-sans text-slate-500 dark:text-slate-400">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
                    </div>

                    {/* Error Banner */}
                    {error && (
                        <div className="mb-8 bg-red-50 dark:bg-red-900/20 border-l-[3px] border-red-500 p-4 rounded-r-lg flex items-start" role="alert">
                            <AlertCircle className="text-red-500 mr-3 mt-0.5 shrink-0" size={20} />
                            <div>
                                <h3 className="font-sans font-bold text-red-600 dark:text-red-400 mb-1 text-sm">Login Gagal</h3>
                                <p className="font-sans text-sm text-red-500 dark:text-red-300">{error}</p>
                            </div>
                        </div>
                    )}

                    {/* Login Form */}
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div>
                            <label className="block font-sans font-semibold text-semudah-primary dark:text-slate-300 mb-2 text-sm" htmlFor="email">Alamat Email</label>
                            <input 
                                id="email"
                                type="email" 
                                required
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                placeholder="admin@semudah.local"
                                className="w-full bg-white dark:bg-slate-800 border border-semudah-secondary dark:border-slate-600 rounded-[8px] px-4 py-3 font-sans text-slate-900 dark:text-white focus:outline-none focus:border-semudah-primary dark:focus:border-semudah-accent focus:ring-1 focus:ring-semudah-primary dark:focus:ring-semudah-accent transition-colors" 
                            />
                        </div>
                        
                        <div>
                            <div className="flex justify-between items-center mb-2">
                                <label className="block font-sans font-semibold text-semudah-primary dark:text-slate-300 text-sm" htmlFor="password">Password</label>
                            </div>
                            <div className="relative">
                                <input 
                                    id="password"
                                    type={showPassword ? 'text' : 'password'}
                                    required
                                    value={password}
                                    onChange={(e) => setPassword(e.target.value)}
                                    placeholder="••••••••"
                                    className="w-full bg-white dark:bg-slate-800 border border-semudah-secondary dark:border-slate-600 rounded-[8px] px-4 py-3 font-sans text-slate-900 dark:text-white focus:outline-none focus:border-semudah-primary dark:focus:border-semudah-accent focus:ring-1 focus:ring-semudah-primary dark:focus:ring-semudah-accent transition-colors" 
                                />
                                <button 
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-semudah-primary dark:hover:text-semudah-accent transition-colors"
                                    aria-label="Toggle password visibility"
                                >
                                    {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
                                </button>
                            </div>
                        </div>
                        
                        <div className="pt-4">
                            <button 
                                type="submit" 
                                disabled={loading}
                                className="w-full bg-semudah-primary dark:bg-semudah-secondary text-white dark:text-slate-900 font-sans font-bold text-base rounded-[8px] py-4 px-6 hover:bg-semudah-primary/90 dark:hover:bg-semudah-secondary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-semudah-primary flex justify-center items-center disabled:opacity-70"
                            >
                                {loading ? 'Processing...' : 'Masuk'}
                                {!loading && <ArrowRight size={20} className="ml-2" />}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default Login;
