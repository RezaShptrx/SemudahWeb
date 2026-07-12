<div class="login-page-wrapper">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .login-page-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            min-height: 100vh;
            background: linear-gradient(135deg, #E0F7FF, #F8FBFF);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            box-sizing: border-box;
            z-index: 99999;
            overflow-y: auto;
        }

        .login-page-wrapper * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .login-container-trans {
            width: 1100px;
            max-width: 100%;
            background: white;
            border-radius: 35px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            box-shadow: 0 20px 40px rgba(0,0,0,.08);
        }

        .left-side-trans {
            background: linear-gradient(135deg, #22C1F1, #2563EB);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-trans {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .left-side-trans h2 {
            font-size: 40px;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .left-side-trans p {
            line-height: 1.8;
            opacity: .95;
            font-size: 15px;
        }

        .right-side-trans {
            padding: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .title-trans {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1E293B;
        }

        .subtitle-trans {
            color: #64748B;
            margin-bottom: 40px;
            font-size: 15px;
        }

        .input-group-trans {
            margin-bottom: 25px;
            text-align: left;
        }

        .input-group-trans label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1E293B;
        }

        .input-box-trans {
            background: #F8FAFC;
            border-radius: 18px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #E2E8F0;
            transition: border-color 0.2s;
        }

        .input-box-trans:focus-within {
            border-color: #22C1F1;
        }

        .input-box-trans svg {
            color: #64748B;
            width: 20px;
            height: 20px;
        }

        .input-box-trans input {
            width: 100%;
            border: none;
            outline: none;
            background: none;
            font-size: 15px;
            color: #1E293B;
        }

        .login-btn-trans {
            width: 100%;
            border: none;
            background: #22C1F1;
            color: white;
            padding: 18px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
            margin-top: 15px;
        }

        .login-btn-trans:hover {
            background: #0EA5E9;
        }

        .footer-text-trans {
            text-align: center;
            margin-top: 25px;
            color: #94A3B8;
            font-size: 14px;
        }

        @media(max-width: 900px) {
            .login-container-trans {
                grid-template-columns: 1fr;
            }

            .left-side-trans {
                display: none;
            }

            .right-side-trans {
                padding: 40px;
            }
        }
    </style>

    <div class="login-container-trans">
        <div class="left-side-trans">
            <div class="logo-trans">
                SEMUDAH
            </div>
            <h2>
                Dashboard Petugas
            </h2>
            <p>
                Kelola pesanan, pembayaran, produk, jasa, dan laporan transaksi dengan mudah melalui dashboard SEMUDAH.
            </p>
        </div>

        <div class="right-side-trans">
            <div class="title-trans">
                Login Admin
            </div>
            <div class="subtitle-trans">
                Masuk menggunakan akun petugas
            </div>

            <form wire:submit="authenticate">
                <div class="input-group-trans">
                    <label>Email</label>
                    <div class="input-box-trans">
                        <i data-lucide="mail"></i>
                        <input type="email" wire:model="data.email" placeholder="admin@semudah.com" required autofocus>
                    </div>
                    @error('data.email')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group-trans">
                    <label>Password</label>
                    <div class="input-box-trans">
                        <i data-lucide="lock"></i>
                        <input type="password" wire:model="data.password" placeholder="********" required>
                    </div>
                    @error('data.password')
                        <span style="color: #ef4444; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="login-btn-trans">
                    Login
                </button>
            </form>

            <div class="footer-text-trans">
                © 2026 SEMUDAH
            </div>
        </div>
    </div>

    <script>
        // Initialize lucide icons when loaded
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        document.addEventListener("livewire:navigated", function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</div>
