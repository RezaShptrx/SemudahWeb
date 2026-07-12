<style>
    /* Google Fonts Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    /* Global Poppins Font */
    html, body, div, span, h1, h2, h3, h4, h5, h6, p, a, th, td, label, input, select, textarea, button {
        font-family: 'Poppins', sans-serif !important;
    }

    /* ===== LIGHT MODE ===== */
    body, 
    .fi-layout, 
    .fi-main,
    .fi-content {
        background-color: #F8FBFF !important;
    }

    /* Cards, sections, stats & tables */
    .fi-section, 
    .fi-card,
    .fi-wi-stats-overview-stat,
    .fi-wi-stats-overview-card,
    .fi-ta-ctn,
    .fi-widget,
    .fi-wi-widget {
        background-color: white !important;
        border-radius: 25px !important;
        border: 1px solid #E2E8F0 !important;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03) !important;
        overflow: hidden !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
    }

    .fi-section:hover,
    .fi-wi-stats-overview-stat:hover,
    .fi-ta-ctn:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
    }

    /* Page Header */
    .fi-header-heading {
        font-size: 30px !important;
        font-weight: 700 !important;
        color: #1E293B !important;
        letter-spacing: -0.5px !important;
    }

    .fi-header-subheading {
        color: #64748B !important;
        font-size: 15px !important;
    }

    /* Table columns headers */
    .fi-ta-header-cell {
        background-color: #F8FAFC !important;
        color: #64748B !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        padding: 15px 20px !important;
    }

    /* Table body rows */
    .fi-ta-row {
        transition: background-color 0.2s ease !important;
    }

    .fi-ta-row:hover {
        background-color: #F8FAFC !important;
    }

    .fi-ta-cell {
        padding: 18px 20px !important;
        font-size: 14px !important;
        color: #334155 !important;
    }

    /* Stat Widgets override */
    .fi-wi-stats-overview-stat {
        padding: 28px !important;
        position: relative !important;
    }

    .fi-wi-stats-overview-stat-value {
        font-size: 30px !important;
        font-weight: 700 !important;
        color: #1E293B !important;
        margin-top: 5px !important;
    }

    .fi-wi-stats-overview-stat-label {
        color: #64748B !important;
        font-size: 14px !important;
        font-weight: 600 !important;
    }

    .fi-wi-stats-overview-stat-description {
        font-size: 12px !important;
        color: #94A3B8 !important;
    }

    /* ===== DARK MODE OVERRIDES ===== */
    .dark body, 
    .dark .fi-layout, 
    .dark .fi-main,
    .dark .fi-content {
        background-color: #0f172a !important;
    }

    .dark .fi-section, 
    .dark .fi-card,
    .dark .fi-wi-stats-overview-stat,
    .dark .fi-wi-stats-overview-card,
    .dark .fi-ta-ctn,
    .dark .fi-widget,
    .dark .fi-wi-widget {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2) !important;
    }

    .dark .fi-section:hover,
    .dark .fi-wi-stats-overview-stat:hover,
    .dark .fi-ta-ctn:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3) !important;
    }

    /* Dark Mode - Page Header */
    .dark .fi-header-heading {
        color: #f1f5f9 !important;
    }

    .dark .fi-header-subheading {
        color: #94a3b8 !important;
    }

    /* Dark Mode - Table */
    .dark .fi-ta-header-cell {
        background-color: #1e293b !important;
        color: #94a3b8 !important;
    }

    .dark .fi-ta-row:hover {
        background-color: #334155 !important;
    }

    .dark .fi-ta-cell {
        color: #cbd5e1 !important;
    }

    /* Dark Mode - Stat Widgets */
    .dark .fi-wi-stats-overview-stat-value {
        color: #f1f5f9 !important;
    }

    .dark .fi-wi-stats-overview-stat-label {
        color: #94a3b8 !important;
    }

    .dark .fi-wi-stats-overview-stat-description {
        color: #64748b !important;
    }

    /* Dark Mode - Sidebar */
    .dark .fi-sidebar {
        background-color: #0f172a !important;
        border-color: #1e293b !important;
    }

    .dark .fi-sidebar-nav-flat-item-label,
    .dark .fi-sidebar-item-label {
        color: #94a3b8 !important;
    }

    .dark .fi-sidebar-item-active .fi-sidebar-item-label,
    .dark .fi-sidebar-nav-flat-item-active .fi-sidebar-nav-flat-item-label {
        color: #22d3ee !important;
    }

    /* Dark Mode - Forms & Inputs */
    .dark .fi-input-wrp,
    .dark .fi-fo-field-wrp input,
    .dark .fi-fo-field-wrp select,
    .dark .fi-fo-field-wrp textarea {
        background-color: #1e293b !important;
        color: #e2e8f0 !important;
        border-color: #334155 !important;
    }

    /* Dark Mode - Scrollbars */
    .dark ::-webkit-scrollbar-track {
        background: #0f172a;
    }
    .dark ::-webkit-scrollbar-thumb {
        background: #334155;
    }
    .dark ::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }

    /* Light Mode - Scrollbars */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #F8FBFF;
    }
    ::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 99px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }
</style>
