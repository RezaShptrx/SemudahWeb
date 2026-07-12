<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Print Dokumen - SEMUDAH' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            /* Vibrant tech gradient */
            background: linear-gradient(-45deg, #e0f2fe, #bae6fd, #e0e7ff, #f3e8ff) !important;
            background-size: 400% 400% !important;
            animation: gradientBG 15s ease infinite !important;
            color: #0f172a !important;
            min-height: 100vh;
        }

        .container-trans {
            max-width: 100%;
            margin: auto;
            padding: 40px 5%;
        }

        .header-trans {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .logo-trans img {
            height: 50px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.05));
        }

        .back-btn-trans {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 15px 25px;
            border-radius: 18px;
            text-decoration: none;
            color: #0ea5e9;
            box-shadow: 0 4px 15px rgba(0,0,0,.03), inset 0 1px 0 rgba(255,255,255,0.6);
            border: 1px solid rgba(255, 255, 255, 0.4);
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .back-btn-trans:hover {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.15), inset 0 1px 0 rgba(255,255,255,1);
        }

        .grid-trans {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .card-trans {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05), 
                        inset 0 1px 0 rgba(255,255,255,0.8);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-trans:hover {
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08), 
                        inset 0 1px 0 rgba(255,255,255,1);
        }

        .section-title-trans {
            font-size: 26px;
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #0f172a, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .upload-box-trans {
            min-height: 220px;
            border: 2px dashed rgba(14, 165, 233, 0.4);
            border-radius: 25px;
            background: rgba(240, 249, 255, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .upload-box-trans:hover {
            background: rgba(224, 242, 254, 0.8);
            border-color: #0ea5e9;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.1);
        }

        .upload-box-trans h3 {
            color: #0284c7;
            margin-bottom: 10px;
            font-size: 20px;
            font-weight: 700;
        }

        .upload-box-trans p {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        .input-group-trans {
            margin-bottom: 25px;
        }

        .input-group-trans label {
            display: block;
            margin-bottom: 12px;
            color: #475569;
            font-size: 15px;
            font-weight: 600;
        }

        .input-trans, .select-trans, .textarea-trans {
            width: 100%;
            padding: 16px 20px;
            font-size: 16px;
            border-radius: 16px;
            border: 2px solid rgba(226, 232, 240, 0.8);
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #1E293B;
            outline: none;
            box-sizing: border-box;
            box-shadow: 0 2px 10px rgba(0,0,0,0.01);
        }

        .input-trans:focus,
        .select-trans:focus,
        .textarea-trans:focus {
            background-color: #fff;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15), 0 4px 15px rgba(0,0,0,0.03);
            transform: translateY(-1px);
        }

        .textarea-trans {
            min-height: 120px;
            resize: none;
        }

        .row-trans {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .summary-trans {
            position: sticky;
            top: 30px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .summary-item-trans {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 16px;
            color: #64748B;
        }

        .summary-item-trans span {
            color: #64748B;
            font-weight: 600;
            font-size: 16px;
        }

        .summary-item-trans .value-trans {
            color: #0F172A;
            font-weight: 800;
            font-size: 18px;
        }

        .total-trans {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .pay-box-trans {
            margin-top: 30px;
        }

        .pay-box-trans h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1E293B;
        }

        .payment-trans {
            background: rgba(255, 255, 255, 0.9);
            padding: 18px;
            border-radius: 18px;
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: 0.2s;
            font-weight: 600;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .payment-trans:hover {
            background: #ffffff;
            border-color: #0ea5e9;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.1);
        }

        .button-trans {
            width: 100%;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            border: none;
            padding: 18px 24px;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 20px -5px rgba(14, 165, 233, 0.4),
                        inset 0 1px 0 rgba(255,255,255,0.2);
            text-align: center;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .button-trans:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(14, 165, 233, 0.5),
                        inset 0 1px 0 rgba(255,255,255,0.3);
            background: linear-gradient(135deg, #0284c7, #2563eb);
        }
        
        .button-trans:active {
            transform: translateY(1px);
        }

        /* --- Dark Mode Overrides --- */
        .dark body {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #0f172a, #334155) !important;
            background-size: 400% 400% !important;
            color: #f1f5f9 !important;
        }
        
        .dark .back-btn-trans {
            background: rgba(30, 41, 59, 0.8);
            color: #38bdf8;
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 15px rgba(0,0,0,.2), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        
        .dark .back-btn-trans:hover {
            background: #1e293b;
            box-shadow: 0 8px 25px rgba(56, 189, 248, 0.15), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .dark .card-trans {
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255,255,255,0.1);
        }

        .dark .card-trans:hover {
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255,255,255,0.15);
        }

        .dark .section-title-trans {
            background: linear-gradient(135deg, #f8fafc, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dark .upload-box-trans {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(56, 189, 248, 0.3);
        }

        .dark .upload-box-trans:hover {
            background: rgba(30, 41, 59, 0.8);
            border-color: #38bdf8;
        }

        .dark .upload-box-trans h3 {
            color: #38bdf8;
        }

        .dark .upload-box-trans p {
            color: #94a3b8;
        }

        .dark .input-group-trans label {
            color: #cbd5e1;
        }

        .dark .input-trans, .dark .select-trans, .dark .textarea-trans {
            background-color: rgba(15, 23, 42, 0.8);
            border-color: rgba(71, 85, 105, 0.8);
            color: #f1f5f9;
        }

        .dark .input-trans:focus, .dark .select-trans:focus, .dark .textarea-trans:focus {
            background-color: #0f172a;
            border-color: #38bdf8;
        }

        .dark .summary-trans {
            background: rgba(30, 41, 59, 0.85);
        }

        .dark .summary-item-trans {
            color: #94a3b8;
        }

        .dark .summary-item-trans span {
            color: #94a3b8;
        }

        .dark .summary-item-trans .value-trans {
            color: #f8fafc;
        }

        .dark .total-trans {
            background: linear-gradient(135deg, #38bdf8, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dark .pay-box-trans h3 {
            color: #f8fafc;
        }

        .dark .payment-trans {
            background: rgba(15, 23, 42, 0.8);
            border-color: rgba(71, 85, 105, 0.8);
            color: #f1f5f9;
        }

        .dark .payment-trans:hover {
            background: #1e293b;
            border-color: #38bdf8;
        }

        @media(max-width: 1000px){
            .container-trans {
                padding: 20px;
            }
            .card-trans {
                padding: 24px;
                border-radius: 24px;
            }
            .grid-trans {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .row-trans {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .header-trans {
                margin-bottom: 24px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .logo-trans img {
                height: 32px;
            }
            .back-btn-trans {
                padding: 12px 20px;
                font-size: 14px;
                border-radius: 14px;
            }
        }

        @media(max-width: 640px){
            .container-trans {
                padding: 16px 12px;
            }
            .card-trans {
                padding: 20px 16px;
                border-radius: 20px;
            }
            .logo-trans {
                font-size: 28px;
            }
            .back-btn-trans {
                padding: 10px 14px;
                font-size: 13px;
                border-radius: 12px;
            }
        }
    </style>
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
