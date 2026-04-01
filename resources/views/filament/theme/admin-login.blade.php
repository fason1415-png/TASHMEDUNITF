<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap');

    .sr-login-body {
        background:
            radial-gradient(circle at 8% 14%, rgba(56, 131, 255, 0.38), transparent 26%),
            radial-gradient(circle at 84% 18%, rgba(33, 194, 255, 0.26), transparent 22%),
            radial-gradient(circle at 88% 88%, rgba(104, 84, 255, 0.28), transparent 24%),
            linear-gradient(140deg, #06113a 0%, #091f57 40%, #102e74 100%);
        font-family: 'Manrope', 'Segoe UI', sans-serif;
        min-height: 100vh;
    }

    .sr-login-body .fi-simple-layout {
        min-height: 100vh;
    }

    .sr-login-body .fi-simple-main-ctn {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.35rem;
    }

    .sr-login-body .fi-simple-main {
        width: min(1100px, 100%);
        max-width: 1100px;
    }

    .sr-login-body .fi-simple-page {
        width: 100%;
    }

    .sr-login-body .fi-simple-page-content {
        padding: 0;
    }

    .sr-login-layout {
        width: 100%;
        display: grid;
        grid-template-columns: 1.08fr 0.92fr;
        border-radius: 1.45rem;
        border: 1px solid rgba(101, 141, 226, 0.44);
        background: rgba(4, 20, 61, 0.66);
        box-shadow: 0 24px 58px rgba(4, 15, 43, 0.44);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .sr-login-brand {
        position: relative;
        padding: 1.9rem 1.95rem 1.8rem;
        background:
            radial-gradient(circle at 86% 86%, rgba(201, 212, 255, 0.3) 0%, rgba(201, 212, 255, 0) 44%),
            linear-gradient(150deg, #2f6ef8 0%, #2ea8ea 54%, #5f72f7 100%);
        color: #f7fbff;
    }

    .sr-login-brand::after {
        content: '';
        position: absolute;
        right: -76px;
        bottom: -86px;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        background: rgba(188, 198, 255, 0.34);
    }

    .sr-login-brand-top {
        display: flex;
        align-items: center;
        gap: 0.82rem;
    }

    .sr-login-brand-logo {
        width: 3.25rem;
        height: 3.25rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: 1.08rem;
        font-weight: 700;
        color: #2c4f9f;
        background: #f7fbff;
        border: 2px solid rgba(255, 255, 255, 0.92);
        box-shadow: 0 10px 24px rgba(11, 29, 75, 0.28);
    }

    .sr-login-brand-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.44rem 0.72rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        border: 1px solid rgba(255, 255, 255, 0.42);
        background: rgba(255, 255, 255, 0.18);
    }

    .sr-login-brand-title {
        margin: 1.25rem 0 0;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: clamp(1.62rem, 2.5vw, 2.28rem);
        line-height: 1.15;
        letter-spacing: -0.025em;
        max-width: 22rem;
    }

    .sr-login-brand-subtitle {
        margin: 0.82rem 0 0;
        font-size: 1rem;
        line-height: 1.55;
        color: rgba(247, 252, 255, 0.92);
        max-width: 30rem;
    }

    .sr-login-brand-points {
        margin: 1.2rem 0 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 0.65rem;
    }

    .sr-login-brand-points li {
        position: relative;
        padding-left: 1.28rem;
        font-size: 0.92rem;
        color: rgba(242, 250, 255, 0.98);
    }

    .sr-login-brand-points li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.44rem;
        width: 0.55rem;
        height: 0.55rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 0 0 0.24rem rgba(255, 255, 255, 0.2);
    }

    .sr-login-auth {
        background: linear-gradient(180deg, rgba(5, 23, 66, 0.92) 0%, rgba(4, 18, 57, 0.88) 100%);
        padding: 1.8rem 1.6rem 1.25rem;
        color: #ecf4ff;
        display: flex;
        flex-direction: column;
        gap: 0.76rem;
    }

    .sr-login-locales {
        display: inline-flex;
        gap: 0.36rem;
        padding: 0.2rem;
        border-radius: 999px;
        border: 1px solid rgba(119, 154, 230, 0.36);
        background: rgba(116, 145, 222, 0.12);
        width: max-content;
        max-width: 100%;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sr-login-locales::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    .sr-login-locale-link {
        text-decoration: none;
        color: #a8bfeb;
        font-size: 0.74rem;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.35rem 0.58rem;
        white-space: nowrap;
        transition: background-color 150ms ease, color 150ms ease;
    }

    .sr-login-locale-link:hover {
        color: #f4f8ff;
        background: rgba(255, 255, 255, 0.12);
    }

    .sr-login-locale-link.is-active {
        color: #fff;
        background: linear-gradient(120deg, #2f6ef8 0%, #4d8dfb 100%);
        box-shadow: 0 10px 22px rgba(47, 110, 248, 0.28);
    }

    .sr-login-title {
        margin: 0.24rem 0 0;
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        font-size: clamp(1.72rem, 2.4vw, 2.42rem);
        line-height: 1.1;
        letter-spacing: -0.02em;
        color: #eaf3ff;
    }

    .sr-login-subtitle {
        margin: 0;
        color: #9eb5dc;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .sr-login-alert {
        border: 1px solid rgba(252, 98, 132, 0.6);
        background: rgba(255, 88, 123, 0.18);
        border-radius: 0.8rem;
        padding: 0.58rem 0.72rem;
        color: #ffd2db;
        font-size: 0.83rem;
        font-weight: 700;
    }

    .sr-login-role-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.52rem;
        margin-bottom: 0.1rem;
    }

    .sr-login-role {
        border-radius: 0.75rem;
        border: 1px solid rgba(120, 156, 236, 0.28);
        background: rgba(121, 152, 219, 0.16);
        color: #b6caee;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.44rem 0.88rem;
    }

    .sr-login-role.is-active {
        color: #fff;
        border-color: transparent;
        background: linear-gradient(120deg, #2f9df8 0%, #5d74ff 100%);
        box-shadow: 0 12px 24px rgba(63, 110, 240, 0.28);
    }

    .sr-login-auth .fi-sc-form {
        margin-top: 0.1rem;
        display: grid;
        gap: 0.72rem;
    }

    .sr-login-auth .fi-fo-field-label {
        color: #98b1da;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .sr-login-auth .fi-input-wrp {
        border-radius: 0.84rem;
        border: 1px solid rgba(140, 169, 232, 0.34);
        background: rgba(230, 240, 255, 0.96);
        min-height: 3.05rem;
    }

    .sr-login-auth .fi-input {
        color: #1d3b6d;
        font-weight: 600;
    }

    .sr-login-auth .fi-input::placeholder {
        color: #7d94be;
    }

    .sr-login-auth .fi-input-wrp .fi-icon {
        color: #446aa8;
    }

    .sr-login-auth .fi-sc-actions {
        margin-top: 0.22rem;
    }

    .sr-login-auth .fi-btn {
        border-radius: 0.82rem;
        min-height: 2.95rem;
        font-size: 1.01rem;
        font-weight: 800;
    }

    .sr-login-auth .fi-btn.fi-color-primary {
        border-color: transparent;
        background: linear-gradient(120deg, #2eb4ef 0%, #5a73ff 100%);
        box-shadow: 0 16px 28px rgba(46, 143, 241, 0.3);
    }

    .sr-login-auth .fi-btn.fi-color-primary:hover {
        filter: brightness(1.06);
    }

    .sr-login-auth .fi-checkbox-input {
        border-color: rgba(131, 161, 221, 0.44);
        background: rgba(16, 42, 93, 0.72);
    }

    .sr-login-auth .fi-fo-field-content > label,
    .sr-login-auth .fi-checkbox-label {
        color: #9ab0d6;
        font-size: 0.84rem;
    }

    .sr-login-auth .fi-fo-field-wrp-error-message,
    .sr-login-auth .fi-fo-field-wrp-error-list {
        color: #ffb8c8;
    }

    .sr-login-copy {
        margin: 0.44rem 0 0;
        text-align: center;
        color: #93add7;
        font-size: 0.75rem;
    }

    @media (max-width: 980px) {
        .sr-login-body .fi-simple-main-ctn {
            padding: 0.95rem;
        }

        .sr-login-layout {
            grid-template-columns: 1fr;
        }

        .sr-login-brand {
            padding: 1.42rem 1.28rem;
        }

        .sr-login-brand::after {
            width: 165px;
            height: 165px;
            right: -56px;
            bottom: -68px;
        }

        .sr-login-auth {
            padding: 1.28rem 1.15rem 1rem;
        }
    }

    @media (max-width: 560px) {
        .sr-login-body .fi-simple-main-ctn {
            padding: 0.55rem;
        }

        .sr-login-layout {
            border-radius: 1.12rem;
        }

        .sr-login-brand-title {
            font-size: clamp(1.34rem, 7vw, 1.66rem);
        }

        .sr-login-brand-subtitle {
            font-size: 0.88rem;
        }

        .sr-login-title {
            font-size: clamp(1.4rem, 8vw, 1.82rem);
        }

        .sr-login-auth .fi-btn {
            min-height: 2.75rem;
            font-size: 0.95rem;
        }

        .sr-login-auth .fi-input-wrp {
            min-height: 2.85rem;
        }
    }
</style>

