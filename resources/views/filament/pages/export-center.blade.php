<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2">
        {{-- Shifokor oylik natijasi --}}
        <div style="background: linear-gradient(135deg, #0d9488, #0f766e); border-radius: 20px; padding: 28px; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; margin: 0;">Shifokor oylik natijasi</h3>
                    <p style="font-size: 12px; opacity: 0.8; margin: 2px 0 0;">Har bir shifokor bo'yicha batafsil hisobot</p>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; opacity: 0.7;">Oy tanlang</label>
                <input type="month" wire:model.live="month"
                       style="width: 100%; margin-top: 6px; padding: 10px 14px; border-radius: 12px; border: 2px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.15); color: white; font-size: 14px; outline: none;">
            </div>
            <a href="{{ route('exports.doctor-monthly', ['month' => $month, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
               style="display: inline-flex; align-items: center; gap: 8px; background: white; color: #0f766e; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; transition: transform 0.2s;"
               onmouseenter="this.style.transform='translateY(-2px)'" onmouseleave="this.style.transform='none'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Excel yuklash
            </a>
        </div>

        {{-- Bo'lim reytingi --}}
        <div style="background: linear-gradient(135deg, #7c3aed, #6d28d9); border-radius: 20px; padding: 28px; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; margin: 0;">Bo'lim reytingi</h3>
                    <p style="font-size: 12px; opacity: 0.8; margin: 2px 0 0;">Bo'limlar samaradorligi taqqoslash</p>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px;">
                <div>
                    <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; opacity: 0.7;">Boshlanish</label>
                    <input type="date" wire:model.live="from"
                           style="width: 100%; margin-top: 6px; padding: 10px 14px; border-radius: 12px; border: 2px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.15); color: white; font-size: 13px; outline: none;">
                </div>
                <div>
                    <label style="font-size: 11px; font-weight: 600; text-transform: uppercase; opacity: 0.7;">Tugash</label>
                    <input type="date" wire:model.live="to"
                           style="width: 100%; margin-top: 6px; padding: 10px 14px; border-radius: 12px; border: 2px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.15); color: white; font-size: 13px; outline: none;">
                </div>
            </div>
            <a href="{{ route('exports.department-ranking', ['from' => $from, 'to' => $to, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
               style="display: inline-flex; align-items: center; gap: 8px; background: white; color: #6d28d9; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; transition: transform 0.2s;"
               onmouseenter="this.style.transform='translateY(-2px)'" onmouseleave="this.style.transform='none'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Excel yuklash
            </a>
        </div>

        {{-- Shikoyat kategoriyalari --}}
        <div style="background: white; border: 2px solid #fde68a; border-radius: 20px; padding: 28px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 44px; height: 44px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Shikoyat kategoriyalari</h3>
                    <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">Og'irlik va status bo'yicha guruhlangan</p>
                </div>
            </div>
            <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                Tanlangan davr uchun shikoyatlar tahlili — kategoriya, soni va jiddiyligi bo'yicha.
            </p>
            <a href="{{ route('exports.complaint-categories', ['from' => $from, 'to' => $to, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
               style="display: inline-flex; align-items: center; gap: 8px; background: #f59e0b; color: white; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; transition: transform 0.2s;"
               onmouseenter="this.style.transform='translateY(-2px)'" onmouseleave="this.style.transform='none'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Excel yuklash
            </a>
        </div>

        {{-- Klinika umumiy PDF --}}
        <div style="background: white; border: 2px solid #e2e8f0; border-radius: 20px; padding: 28px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #1e293b, #475569);"></div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 44px; height: 44px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#334155" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Klinika umumiy hisobot</h3>
                    <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">Rahbariyat uchun qisqa PDF hisobot</p>
                </div>
            </div>
            <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px;">
                Javoblar soni, sifat ko'rsatkichlari, shikoyatlar va eskalatsiyalar — bir sahifada.
            </p>
            <a href="{{ route('exports.clinic-summary-pdf', ['from' => $from, 'to' => $to, 'clinic_id' => auth()->user()?->isSuperAdmin() ? request('clinic_id') : null]) }}"
               style="display: inline-flex; align-items: center; gap: 8px; background: #1e293b; color: white; padding: 10px 20px; border-radius: 12px; font-size: 14px; font-weight: 700; text-decoration: none; transition: transform 0.2s;"
               onmouseenter="this.style.transform='translateY(-2px)'" onmouseleave="this.style.transform='none'">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                PDF yuklash
            </a>
        </div>
    </div>
</x-filament-panels::page>
