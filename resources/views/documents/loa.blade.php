@php
    $participants = $details['participants'] ?? [[
        'student_number' => $details['student_number'] ?? '',
        'full_name' => $details['full_name'] ?? '',
        'study_program' => $details['study_program'] ?? '',
    ]];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LoA Magang - {{ $details['school_name'] }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#edf2f7;color:#28384d;font-family:Arial,Helvetica,sans-serif}.loa-toolbar{position:sticky;top:0;z-index:2;display:flex;justify-content:space-between;align-items:center;gap:12px;padding:14px max(18px,calc((100vw - 980px)/2));background:#ffffffed;border-bottom:1px solid #dfe7ef;backdrop-filter:blur(12px)}.loa-toolbar a,.loa-toolbar button{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:42px;padding:0 15px;border:1px solid #d5e0eb;border-radius:10px;background:#fff;color:#304a68;font-weight:700;text-decoration:none;cursor:pointer}.loa-toolbar button:disabled{cursor:wait;opacity:.65}.loa-toolbar .loa-download{border-color:#385e8a;background:#385e8a;color:#fff}.loa-toolbar-actions{display:flex;gap:8px}.loa-sheet{width:min(210mm,calc(100vw - 32px));min-height:297mm;margin:28px auto;padding:25mm 24mm 22mm;background:#fff;box-shadow:0 18px 50px #1d35521c;font-family:Georgia,'Times New Roman',serif;color:#1d2733}.loa-letterhead{display:flex;justify-content:flex-end;align-items:center;gap:14px;min-height:28mm}.loa-letterhead-copy{text-align:right;font-style:italic;font-size:14px}.loa-letterhead img{width:30mm;height:auto;max-height:24mm;object-fit:contain}.loa-title{text-align:center;margin:14mm 0 4mm;font-size:18px;text-decoration:underline;letter-spacing:.5px}.loa-number{text-align:center;margin:0 0 10mm;font-size:12px}.loa-intro{margin:0 0 4mm;font-size:14px}.loa-table{width:100%;border-collapse:collapse;margin:0 0 9mm;font-size:12px}.loa-table th,.loa-table td{border:1px solid #465262;padding:11px 9px;text-align:left}.loa-table th{text-align:center;font-weight:700}.loa-body{font-size:13px;line-height:1.8;text-align:justify}.loa-body p{margin:0 0 6mm}.loa-schedule{margin:6mm 0 7mm;padding-left:8mm}.loa-schedule div{display:grid;grid-template-columns:28mm 1fr;margin:2mm 0}.loa-signature{width:65mm;margin:12mm 0 0 auto;text-align:center;font-size:13px;line-height:1.6}.loa-signature-space{height:24mm}.loa-signature strong{display:block;text-decoration:underline}.loa-digital-note{text-align:center;margin:18mm 0 0;color:#8492a2;font:10px Arial,sans-serif}@media(max-width:640px){.loa-toolbar{position:static;align-items:stretch;flex-direction:column;padding:12px}.loa-toolbar-actions{display:grid;grid-template-columns:1fr 1fr}.loa-toolbar a,.loa-toolbar button{font-size:12px}.loa-sheet{width:100%;min-height:0;margin:0;padding:14mm 10mm;box-shadow:none}.loa-table{font-size:10px}.loa-table th,.loa-table td{padding:7px 5px}.loa-body{font-size:12px}.loa-letterhead img{width:25mm}}@media print{@page{size:A4;margin:0}body{background:#fff}.loa-toolbar{display:none}.loa-sheet{width:210mm;min-height:297mm;margin:0;padding:25mm 24mm 22mm;box-shadow:none}}
    </style>
    <style>
        .loa-signature-space{display:flex;align-items:center;justify-content:center;height:27mm}
        .loa-signature-space img{width:42mm;height:26mm;object-fit:contain}
        .loa-download-error{position:fixed;right:20px;bottom:20px;z-index:5;max-width:min(420px,calc(100vw - 40px));margin:0;padding:12px 16px;border:1px solid #f1b6b3;border-radius:10px;background:#fff2f1;color:#9b1c16;font:600 14px Arial,sans-serif}
        .loa-download-status{margin:10px auto;color:#52657b;font:600 13px Arial,sans-serif;text-align:center}
    </style>
</head>
<body>
    <nav class="loa-toolbar" aria-label="Aksi dokumen">
        <a href="{{ $backUrl }}">Kembali</a>
        <div class="loa-toolbar-actions">
            <button type="button" id="downloadPng" disabled>Unduh PNG</button>
            <button type="button" class="loa-download" id="downloadPdf" disabled>Unduh PDF</button>
        </div>
    </nav>
    <p class="loa-download-status" id="loaDownloadStatus" role="status">Menyiapkan berkas unduhan...</p>
    <p class="loa-download-error" id="loaDownloadError" role="alert" hidden></p>

    <main class="loa-sheet" id="loaSheet">
        <header class="loa-letterhead">
            <div class="loa-letterhead-copy">Strive For Solid Future</div>
            <img src="{{ asset('image/ewf-logo.png') }}" alt="Logo Equityworld Futures">
        </header>
        <h1 class="loa-title">SURAT KETERANGAN MAGANG</h1>
        <p class="loa-number">Nomor: {{ $details['letter_number'] }}</p>
        <p class="loa-intro">Menyatakan bahwa:</p>
        <table class="loa-table">
            <thead><tr><th>NPM / NIS</th><th>NAMA</th><th>PROGRAM STUDI</th></tr></thead>
            <tbody>
                @foreach ($participants as $participant)
                    <tr><td>{{ $participant['student_number'] }}</td><td>{{ $participant['full_name'] }}</td><td>{{ $participant['study_program'] }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="loa-body">
            <p>{{ $details['school_name'] }}{{ filled($details['faculty']) ? ', '.$details['faculty'] : '' }}, tercatat sebagai {{ count($participants) > 1 ? 'para peserta magang' : 'peserta magang' }} pada {{ $details['company_name'] }}{{ filled($details['company_location']) ? ' di '.$details['company_location'] : '' }} di bidang {{ $details['internship_field'] }} untuk periode {{ \Illuminate\Support\Carbon::parse($details['period_start'])->locale('id')->translatedFormat('d F Y') }} sampai {{ \Illuminate\Support\Carbon::parse($details['period_end'])->locale('id')->translatedFormat('d F Y') }}.</p>
            <p>Selama program berlangsung, peserta mengikuti jadwal kerja sebagai berikut:</p>
            <div class="loa-schedule"><div><strong>Hari</strong><span>: {{ $details['work_days'] }}</span></div><div><strong>Pukul</strong><span>: {{ $details['work_hours'] }}</span></div></div>
            <p>{{ $details['letter_body'] }}</p>
            <p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>
        <div class="loa-signature">
            <div>{{ $details['letter_city'] }}, {{ \Illuminate\Support\Carbon::parse($details['letter_date'])->locale('id')->translatedFormat('d F Y') }}</div>
            <strong>{{ $details['signer_title'] }}</strong>
            <div class="loa-signature-space"><img src="{{ asset('image/ttd-signature.png') }}" alt="Tanda tangan mentor"></div>
            <strong>{{ $details['signer_name'] }}</strong>
            <div>{{ $details['company_name'] }}</div>
        </div>
        <p class="loa-digital-note">Dokumen ini diterbitkan secara digital melalui portal Karir PT Equityworld Futures.</p>
    </main>

    <script>
        (() => {
            const details = @json($details);
            const participants = Array.isArray(details.participants) && details.participants.length
                ? details.participants
                : [{
                    student_number: details.student_number || '',
                    full_name: details.full_name || '',
                    study_program: details.study_program || ''
                }];
            const canvasWidth = 1275;
            const canvasHeight = 1800;
            const encoder = new TextEncoder();

            function wrappedText(ctx, text, x, y, maxWidth, lineHeight) {
                const words = String(text || '').split(/\s+/);
                let line = '';
                for (const word of words) {
                    const candidate = line ? line + ' ' + word : word;
                    if (line && ctx.measureText(candidate).width > maxWidth) {
                        ctx.fillText(line, x, y);
                        y += lineHeight;
                        line = word;
                    } else {
                        line = candidate;
                    }
                }
                if (line) {
                    ctx.fillText(line, x, y);
                    y += lineHeight;
                }
                return y;
            }

            function makeCanvas() {
                return new Promise((resolve, reject) => {
                    const canvas = document.createElement('canvas');
                    canvas.width = canvasWidth;
                    canvas.height = canvasHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = '#fff';
                    ctx.fillRect(0, 0, canvasWidth, canvasHeight);
                    ctx.fillStyle = '#26374d';

                    const logo = new Image();
                    const signature = new Image();
                    let logoLoaded = false;
                    let signatureLoaded = false;

                    const renderDocument = () => {
                        if (!logoLoaded || !signatureLoaded) return;
                        try {
                            if (logo.naturalWidth) ctx.drawImage(logo, 1010, 65, 155, 115);
                            drawDocument(ctx, canvas, signature);
                        } catch (error) {
                            reject(error);
                        }
                    };
                    logo.onload = () => {
                        logoLoaded = true;
                        renderDocument();
                    };
                    logo.onerror = () => {
                        logoLoaded = true;
                        renderDocument();
                    };
                    signature.onload = () => {
                        signatureLoaded = true;
                        renderDocument();
                    };
                    signature.onerror = () => reject(new Error('Foto tanda tangan mentor tidak dapat dimuat.'));
                    logo.src = @json(asset('image/ewf-logo.png'));
                    signature.src = @json(asset('image/ttd-signature.png'));

                    function drawDocument(context, output, signatureImage) {
                        context.fillStyle = '#344761';
                        context.textAlign = 'right';
                        context.font = 'italic 22px Georgia';
                        context.fillText('Strive For Solid Future', 985, 145);
                        context.textAlign = 'center';
                        context.font = 'bold 35px Georgia';
                        context.fillText('SURAT KETERANGAN MAGANG', 637, 330);
                        context.beginPath(); context.moveTo(285, 345); context.lineTo(990, 345); context.strokeStyle = '#344761'; context.lineWidth = 2; context.stroke();
                        context.font = '22px Georgia';
                        context.fillText('Nomor: ' + details.letter_number, 637, 390);
                        context.textAlign = 'left';
                        context.font = '24px Georgia';
                        context.fillText('Menyatakan bahwa:', 110, 485);

                        const x = 110, y = 520, widths = [285, 440, 330], headerHeight = 60, rowHeight = 72;
                        const headers = ['NPM / NIS', 'NAMA', 'PROGRAM STUDI'];
                        const tableWidth = widths.reduce((total, width) => total + width, 0);
                        const tableHeight = headerHeight + rowHeight * participants.length;
                        context.lineWidth = 2; context.strokeStyle = '#465262'; context.fillStyle = '#24364d';
                        context.strokeRect(x, y, tableWidth, tableHeight);
                        context.textAlign = 'center'; context.font = 'bold 22px Georgia';
                        let xPos = x;
                        headers.forEach((heading, index) => {
                            context.fillText(heading, xPos + widths[index] / 2, y + 38);
                            xPos += widths[index];
                            if (index < widths.length - 1) {
                                context.beginPath(); context.moveTo(xPos, y); context.lineTo(xPos, y + tableHeight); context.stroke();
                            }
                        });
                        context.beginPath(); context.moveTo(x, y + headerHeight); context.lineTo(x + tableWidth, y + headerHeight); context.stroke();
                        participants.forEach((participant, rowIndex) => {
                            const rowY = y + headerHeight + rowHeight * rowIndex;
                            if (rowIndex > 0) {
                                context.beginPath(); context.moveTo(x, rowY); context.lineTo(x + tableWidth, rowY); context.stroke();
                            }
                            const values = [participant.student_number, participant.full_name, participant.study_program];
                            let cellX = x;
                            values.forEach((value, columnIndex) => {
                                context.textAlign = 'left'; context.font = '20px Georgia';
                                wrappedText(context, value, cellX + 12, rowY + 30, widths[columnIndex] - 24, 24);
                                cellX += widths[columnIndex];
                            });
                        });

                        let lineY = y + tableHeight + 58;
                        context.fillStyle = '#26313d'; context.textAlign = 'left'; context.font = '25px Georgia';
                        const intro = details.school_name + (details.faculty ? ', ' + details.faculty : '') + ', tercatat sebagai ' + (participants.length > 1 ? 'para peserta magang' : 'peserta magang') + ' pada ' + details.company_name + (details.company_location ? ' di ' + details.company_location : '') + ' di bidang ' + details.internship_field + ' untuk periode ' + formatDate(details.period_start) + ' sampai ' + formatDate(details.period_end) + '.';
                        lineY = wrappedText(context, intro, 110, lineY, 1055, 42) + 28;
                        lineY = wrappedText(context, 'Selama program berlangsung, peserta mengikuti jadwal kerja sebagai berikut:', 110, lineY, 1055, 42) + 20;
                        context.font = '24px Georgia';
                        context.fillText('Hari     : ' + details.work_days, 145, lineY); lineY += 43;
                        context.fillText('Pukul   : ' + details.work_hours, 145, lineY); lineY += 72;
                        lineY = wrappedText(context, details.letter_body, 110, lineY, 1055, 42) + 30;
                        lineY = wrappedText(context, 'Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', 110, lineY, 1055, 42) + 45;
                        context.textAlign = 'center'; context.font = '24px Georgia';
                        context.fillText(details.letter_city + ', ' + formatDate(details.letter_date), 905, lineY); lineY += 45;
                        context.font = 'bold 24px Georgia'; context.fillText(details.signer_title, 905, lineY); lineY += 12;
                        context.drawImage(signatureImage, 830, lineY, 150, 150); lineY += 160;
                        context.fillText(details.signer_name, 905, lineY); lineY += 35;
                        context.font = '22px Georgia'; context.fillText(details.company_name, 905, lineY);
                        resolve(output);
                    }
                });
            }

            function formatDate(value) {
                return new Intl.DateTimeFormat('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}).format(new Date(value + 'T12:00:00'));
            }

            function downloadBlob(blob, filename) {
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = filename;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                link.remove();
                setTimeout(() => URL.revokeObjectURL(url), 60000);
            }

            function canvasToBlob(canvas, type, quality, message) {
                return new Promise((resolve, reject) => {
                    canvas.toBlob(file => file ? resolve(file) : reject(new Error(message)), type, quality);
                });
            }

            async function prepareDownloadFiles() {
                const canvas = await makeCanvas();
                const png = await canvasToBlob(canvas, 'image/png', undefined, 'LoA gagal disiapkan sebagai PNG.');
                const jpeg = await canvasToBlob(canvas, 'image/jpeg', .94, 'LoA gagal disiapkan sebagai PDF.');
                const pdf = await createPdfBlob(jpeg);

                return {png, pdf};
            }

            async function createPdfBlob(jpgBlob) {
                const jpeg = new Uint8Array(await jpgBlob.arrayBuffer());
                const parts = [];
                const offsets = [0];
                let length = 0;
                const add = bytes => { parts.push(bytes); length += bytes.length; };
                const addText = value => add(encoder.encode(value));
                addText('%PDF-1.4\n');
                const object = (number, body) => { offsets[number] = length; addText(number + ' 0 obj\n' + body + '\nendobj\n'); };
                object(1, '<< /Type /Catalog /Pages 2 0 R >>');
                object(2, '<< /Type /Pages /Kids [3 0 R] /Count 1 >>');
                object(3, '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Resources << /XObject << /Im0 4 0 R >> >> /Contents 5 0 R >>');
                offsets[4] = length;
                addText('4 0 obj\n<< /Type /XObject /Subtype /Image /Width ' + canvasWidth + ' /Height ' + canvasHeight + ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ' + jpeg.length + ' >>\nstream\n');
                add(jpeg);
                addText('\nendstream\nendobj\n');
                const content = 'q\n595.28 0 0 841.89 0 0 cm\n/Im0 Do\nQ';
                object(5, '<< /Length ' + encoder.encode(content).length + ' >>\nstream\n' + content + '\nendstream');
                const xrefOffset = length;
                addText('xref\n0 6\n0000000000 65535 f \n');
                for (let i = 1; i <= 5; i++) addText(String(offsets[i]).padStart(10, '0') + ' 00000 n \n');
                addText('trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n' + xrefOffset + '\n%%EOF');
                return new Blob(parts, {type: 'application/pdf'});
            }

            const downloadError = document.getElementById('loaDownloadError');
            const downloadStatus = document.getElementById('loaDownloadStatus');
            const downloadPngButton = document.getElementById('downloadPng');
            const downloadPdfButton = document.getElementById('downloadPdf');
            const participantNumber = participants[0]?.student_number || 'peserta';
            let downloadFiles = null;
            const reportDownloadError = error => {
                downloadError.textContent = error instanceof Error ? error.message : 'Dokumen gagal dibuat.';
                downloadError.hidden = false;
                downloadStatus.hidden = true;
            };
            downloadPngButton.addEventListener('click', () => {
                if (downloadFiles) downloadBlob(downloadFiles.png, 'LoA-' + participantNumber + '.png');
            });
            downloadPdfButton.addEventListener('click', () => {
                if (downloadFiles) downloadBlob(downloadFiles.pdf, 'LoA-' + participantNumber + '.pdf');
            });
            prepareDownloadFiles().then(files => {
                downloadFiles = files;
                downloadPngButton.disabled = false;
                downloadPdfButton.disabled = false;
                downloadStatus.hidden = true;
            }).catch(reportDownloadError);
        })();
    </script>
</body>
</html>
