import './bootstrap';
import React, { useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route, Navigate, useNavigate } from 'react-router-dom';
import axios from 'axios';
import AdminLayout from './Components/AdminLayout';
import Login from './Pages/Auth/Login';
import Dashboard from './Pages/Dashboard';
import Categories from './Pages/Categories';
import Services from './Pages/Services';
import Users from './Pages/Users';
import PromoCodes from './Pages/PromoCodes';
import Products from './Pages/Products';
import Orders from './Pages/Orders';
import Attendances from './Pages/Attendances';
import DailyArchives from './Pages/DailyArchives';
import ProductFormBuilder from './Pages/ProductFormBuilder';
import SettingsConfig from './Pages/SettingsConfig';

// Global Axios Interceptor Setup
const AxiosInterceptorSetup = ({ children }) => {
    const navigate = useNavigate();

    useEffect(() => {
        const requestInterceptor = axios.interceptors.request.use(
            (config) => {
                const token = localStorage.getItem('token');
                if (token) {
                    config.headers['Authorization'] = `Bearer ${token}`;
                }
                return config;
            },
            (error) => Promise.reject(error)
        );

        const responseInterceptor = axios.interceptors.response.use(
            (response) => response,
            (error) => {
                if (error.response && error.response.status === 401) {
                    localStorage.removeItem('token');
                    navigate('/admin/login');
                }
                return Promise.reject(error);
            }
        );

        return () => {
            axios.interceptors.request.eject(requestInterceptor);
            axios.interceptors.response.eject(responseInterceptor);
        };
    }, [navigate]);

    return children;
};

const App = () => {
    return (
        <Router>
            <AxiosInterceptorSetup>
                <Routes>
                    <Route path="/admin/login" element={<Login />} />
                    
                    {/* Protected Routes Wrapper */}
                    <Route path="/admin/*" element={
                        <AdminLayout>
                            <Routes>
                                <Route path="dashboard" element={<Dashboard />} />
                                <Route path="products/:id/form" element={<ProductFormBuilder />} />
                                <Route path="orders" element={<Orders />} />
                                <Route path="categories" element={<Categories />} />
                                <Route path="services" element={<Services />} />
                                <Route path="users" element={<Users />} />
                                <Route path="promo-codes" element={<PromoCodes />} />
                                <Route path="products" element={<Products />} />
                                <Route path="attendances" element={<Attendances />} />
                                <Route path="archives" element={<DailyArchives />} />
                                <Route path="settings" element={<SettingsConfig />} />
                            </Routes>
                        </AdminLayout>
                    } />
                    
                    <Route path="/admin" element={<Navigate to="/admin/dashboard" />} />
                </Routes>
            </AxiosInterceptorSetup>
        </Router>
    );
};

if (document.getElementById('react-root')) {
    // Initialize dark mode
    if (localStorage.getItem('dark') === 'true' || (!('dark' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }
    
    createRoot(document.getElementById('react-root')).render(<App />);
}
