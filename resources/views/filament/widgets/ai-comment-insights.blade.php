<x-filament-widgets::widget>
    @php
        $problems = $analysis['problems'] ?? [];
        $positive = $analysis['positive'] ?? [];
        $stats = $analysis['stats'] ?? ['total' => 0, 'negative' => 0, 'positive' => 0, 'neutral' => 0];
        $summary = $analysis['summary'] ?? '';
        $total = $stats['total'] ?? 0;
        $negCount = $stats['negative'] ?? 0;
        $posCount = $stats['positive'] ?? 0;
        $neutralCount = $stats['neutral'] ?? ($total - $negCount - $posCount);
    @endphp

    <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden;" class="dark:bg-gray-900 dark:border-gray-700">
        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #7c3aed, #6d28d9); padding: 20px 24px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="color: white; font-size: 16px; font-weight: 700; margin: 0;">AI Tahlil — Bemor izohlari</h3>
                    <p style="color: rgba(255,255,255,0.7); font-size: 12px; margin: 2px 0 0;">OpenAI orqali real vaqtda tahlil (oxirgi 30 kun)</p>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <span style="background: rgba(255,255,255,0.15); color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                    {{ $total }} ta izoh
                </span>
            </div>
        </div>

        <div style="padding: 20px 24px;">
            {{-- Stats bar --}}
            @if($total > 0)
                <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                    <div style="flex: 1; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 16px; text-align: center;">
                        <div style="font-size: 22px; font-weight: 800; color: #dc2626;">{{ $negCount }}</div>
                        <div style="font-size: 11px; color: #991b1b; font-weight: 600; text-transform: uppercase;">Salbiy</div>
                    </div>
                    <div style="flex: 1; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px 16px; text-align: center;">
                        <div style="font-size: 22px; font-weight: 800; color: #16a34a;">{{ $posCount }}</div>
                        <div style="font-size: 11px; color: #166534; font-weight: 600; text-transform: uppercase;">Ijobiy</div>
                    </div>
                    <div style="flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; text-align: center;">
                        <div style="font-size: 22px; font-weight: 800; color: #64748b;">{{ $neutralCount }}</div>
                        <div style="font-size: 11px; color: #475569; font-weight: 600; text-transform: uppercase;">Neytral</div>
                    </div>
                </div>
            @endif

            {{-- AI Summary --}}
            @if($summary)
                <div style="background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; display: flex; gap: 10px; align-items: flex-start;">
                    <span style="font-size: 18px; margin-top: 1px;">🤖</span>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #7c3aed; text-transform: uppercase; margin-bottom: 4px;">AI Xulosa</div>
                        <p style="font-size: 13px; color: #334155; line-height: 1.6; margin: 0;">{{ $summary }}</p>
                    </div>
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                {{-- Problems --}}
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: #dc2626; margin: 0 0 12px; display: flex; align-items: center; gap: 6px;">
                        <span style="display: inline-flex; width: 6px; height: 6px; background: #dc2626; border-radius: 50%;"></span>
                        Aniqlangan muammolar
                    </h4>

                    @forelse ($problems as $problem)
                        @php
                            $sevColor = match($problem['severity'] ?? 'medium') {
                                'high' => ['bg' => '#fef2f2', 'border' => '#fecaca', 'badge' => '#dc2626', 'badgeBg' => '#fee2e2'],
                                'medium' => ['bg' => '#fffbeb', 'border' => '#fde68a', 'badge' => '#d97706', 'badgeBg' => '#fef3c7'],
                                default => ['bg' => '#f8fafc', 'border' => '#e2e8f0', 'badge' => '#64748b', 'badgeBg' => '#f1f5f9'],
                            };
                            $sevText = match($problem['severity'] ?? 'medium') {
                                'high' => 'Yuqori',
                                'medium' => 'O\'rta',
                                default => 'Past',
                            };
                        @endphp
                        <div style="background: {{ $sevColor['bg'] }}; border: 1px solid {{ $sevColor['border'] }}; border-radius: 12px; padding: 12px 14px; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <span style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $problem['issue'] ?? '' }}</span>
                                <div style="display: flex; gap: 6px;">
                                    <span style="background: {{ $sevColor['badgeBg'] }}; color: {{ $sevColor['badge'] }}; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700;">{{ $sevText }}</span>
                                    @if(isset($problem['count']))
                                        <span style="background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700;">{{ $problem['count'] }}x</span>
                                    @endif
                                </div>
                            </div>
                            <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.5;">{{ $problem['description'] ?? '' }}</p>
                            @if(!empty($problem['doctors']))
                                <div style="margin-top: 6px; display: flex; flex-wrap: wrap; gap: 4px;">
                                    @foreach($problem['doctors'] as $doc)
                                        <span style="background: white; border: 1px solid #e2e8f0; padding: 1px 8px; border-radius: 8px; font-size: 10px; color: #475569;">{{ $doc }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; text-align: center;">
                            <span style="font-size: 24px;">✅</span>
                            <p style="font-size: 12px; color: #166534; margin: 6px 0 0; font-weight: 600;">Jiddiy muammolar aniqlanmadi</p>
                        </div>
                    @endforelse
                </div>

                {{-- Positive --}}
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: #16a34a; margin: 0 0 12px; display: flex; align-items: center; gap: 6px;">
                        <span style="display: inline-flex; width: 6px; height: 6px; background: #16a34a; border-radius: 50%;"></span>
                        Ijobiy fikrlar
                    </h4>

                    @forelse ($positive as $pos)
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px 14px; margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                <span style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $pos['theme'] ?? '' }}</span>
                                @if(isset($pos['count']))
                                    <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700;">{{ $pos['count'] }}x</span>
                                @endif
                            </div>
                            <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.5;">{{ $pos['description'] ?? '' }}</p>
                        </div>
                    @empty
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; text-align: center;">
                            <span style="font-size: 24px;">💬</span>
                            <p style="font-size: 12px; color: #64748b; margin: 6px 0 0;">Ijobiy izohlar hali yetarli emas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
