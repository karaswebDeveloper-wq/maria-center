<style>
    @font-face {
        font-family: 'ArabicFont';
        src: url('{{ storage_path('fonts/NotoNaskhArabic-Regular.ttf') }}') format('truetype');
        font-weight: normal;
    }
    @font-face {
        font-family: 'ArabicFont';
        src: url('{{ storage_path('fonts/NotoNaskhArabic-Bold.ttf') }}') format('truetype');
        font-weight: bold;
    }
    body { font-family: 'ArabicFont', sans-serif; direction: rtl; text-align: right; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: right; }
    thead th { background: #f3f4f6; }
    tfoot td { background: #f9fafb; font-weight: bold; }
    h1 { font-size: 16px; margin-bottom: 4px; }
    h2 { font-size: 13px; margin: 14px 0 2px; }
    .meta { color: #666; font-size: 11px; margin-bottom: 10px; }
</style>