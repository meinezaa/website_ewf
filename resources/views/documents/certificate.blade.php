<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Magang - {{ $details['full_name'] }}</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;background:#edf2f7;color:#161d23;font-family:Arial,Helvetica,sans-serif}
        .certificate-toolbar{position:sticky;top:0;z-index:5;display:flex;justify-content:space-between;align-items:center;gap:12px;padding:14px max(18px,calc((100vw - 1250px)/2));background:#ffffffed;border-bottom:1px solid #dfe7ef;backdrop-filter:blur(12px)}
        .certificate-toolbar a,.certificate-toolbar button{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:42px;padding:0 15px;border:1px solid #d5e0eb;border-radius:10px;background:#fff;color:#304a68;font-weight:700;text-decoration:none;cursor:pointer}
        .certificate-toolbar .certificate-download{border-color:#e96e1d;background:#e96e1d;color:#fff}
        .certificate-toolbar button:disabled{cursor:wait;opacity:.65}
        .certificate-toolbar-actions{display:flex;gap:8px}
        .certificate-sheet{position:relative;width:min(1250px,calc(100vw - 32px));aspect-ratio:1400 / 990;margin:28px auto;overflow:hidden;background:#fff;box-shadow:0 18px 50px #1d35521c}
        .certificate-preview{display:block;width:100%;height:100%;background:#fff}
        .certificate-accessible-content{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;clip-path:inset(50%);border:0}
        .certificate-download-error{position:fixed;right:20px;bottom:20px;z-index:5;max-width:min(420px,calc(100vw - 40px));margin:0;padding:12px 16px;border:1px solid #f1b6b3;border-radius:10px;background:#fff2f1;color:#9b1c16;font:600 14px Arial,sans-serif}
        .certificate-download-status{margin:10px auto;color:#52657b;font:600 13px Arial,sans-serif;text-align:center}
        @media(max-width:760px){.certificate-toolbar{position:static;align-items:stretch;flex-direction:column;padding:12px}.certificate-toolbar-actions{display:grid;grid-template-columns:1fr 1fr}.certificate-toolbar a,.certificate-toolbar button{font-size:12px}.certificate-sheet{width:100%;aspect-ratio:1400 / 990;margin:0}}
        @media print{@page{size:A4 landscape;margin:0}body{background:#fff}.certificate-toolbar,.certificate-download-status,.certificate-download-error{display:none}.certificate-sheet{width:297mm;height:210mm;aspect-ratio:auto;margin:0;box-shadow:none}}
    </style>
</head>
<body>
    <nav class="certificate-toolbar" aria-label="Aksi dokumen">
        <a href="{{ $backUrl }}">Kembali</a>
        <div class="certificate-toolbar-actions">
            <button type="button" id="downloadPng" disabled>Unduh PNG</button>
            <button type="button" class="certificate-download" id="downloadPdf" disabled>Unduh PDF</button>
        </div>
    </nav>
    <p class="certificate-download-status" id="certificateDownloadStatus" role="status">Menyiapkan berkas unduhan...</p>
    <p class="certificate-download-error" id="certificateDownloadError" role="alert" hidden></p>

    <main class="certificate-sheet" id="certificateSheet">
        <canvas
            class="certificate-preview"
            id="certificatePreview"
            width="1400"
            height="990"
            role="img"
            aria-label="Sertifikat magang untuk {{ $details['full_name'] }}"
            data-logo-src="{{ asset('image/ewf-logo.png') }}"
            data-signature-src="{{ asset('image/ttd-signature.png') }}"
        >SERTIFIKAT MAGANG. Diberikan kepada {{ $details['full_name'] }}. Periode Magang {{ \Illuminate\Support\Carbon::parse($details['period_start'])->locale('id')->translatedFormat('d F Y') }} sampai {{ \Illuminate\Support\Carbon::parse($details['period_end'])->locale('id')->translatedFormat('d F Y') }}. Ditandatangani {{ $details['signer_name'] }}.</canvas>
        <div class="certificate-accessible-content">
            <h1>SERTIFIKAT MAGANG</h1>
            <p>Diberikan kepada {{ $details['full_name'] }}.</p>
            <p>Telah mengikuti dan menyelesaikan Program Magang di {{ $details['company_name'] }}.</p>
            <p>Periode Magang: {{ \Illuminate\Support\Carbon::parse($details['period_start'])->locale('id')->translatedFormat('d F Y') }} sampai {{ \Illuminate\Support\Carbon::parse($details['period_end'])->locale('id')->translatedFormat('d F Y') }}.</p>
            <p>{{ $details['signer_title'] }}: {{ $details['signer_name'] }}.</p>
            <p>{{ $details['certificate_number'] }}</p>
        </div>
    </main>

    <script>
        (() => {
            const details = @json($details);
            const canvasWidth = 1400;
            const canvasHeight = 990;
            const encoder = new TextEncoder();

            function formatDate(value) {
                return new Intl.DateTimeFormat('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}).format(new Date(value + 'T12:00:00'));
            }

            function makeCanvas() {
                return new Promise((resolve, reject) => {
                    const canvas = document.getElementById('certificatePreview');
                    if (!canvas) {
                        reject(new Error('Pratinjau sertifikat tidak ditemukan.'));
                        return;
                    }
                    canvas.width = canvasWidth;
                    canvas.height = canvasHeight;
                    const context = canvas.getContext('2d');
                    if (!context) {
                        reject(new Error('Pratinjau sertifikat tidak dapat ditampilkan.'));
                        return;
                    }
                    context.fillStyle = '#fff';
                    context.fillRect(0, 0, canvasWidth, canvasHeight);

                    const logo = new Image();
                    const signature = new Image();
                    let logoLoaded = false;
                    let signatureLoaded = false;
                    const renderDocument = () => {
                        if (!logoLoaded || !signatureLoaded) return;
                        try {
                            drawDocument(context, logo, signature);
                            resolve(canvas);
                        } catch (error) {
                            reject(error);
                        }
                    };
                    logo.onload = () => {
                        logoLoaded = true;
                        renderDocument();
                    };
                    logo.onerror = () => reject(new Error('Logo EWF tidak dapat dimuat.'));
                    signature.onload = () => {
                        signatureLoaded = true;
                        renderDocument();
                    };
                    signature.onerror = () => reject(new Error('Foto tanda tangan mentor tidak dapat dimuat.'));
                    logo.src = canvas.dataset.logoSrc;
                    signature.src = canvas.dataset.signatureSrc;
                });
            }

            function drawDocument(context, logo, signature) {
                context.fillStyle = '#f17421';
                context.beginPath();
                context.moveTo(0, 0); context.lineTo(canvasWidth, 0); context.lineTo(canvasWidth, 54);
                context.bezierCurveTo(1000, 115, 680, -10, 0, 74); context.fill();
                context.fillStyle = '#ffcc58';
                context.beginPath(); context.moveTo(0, 57); context.bezierCurveTo(420, -3, 900, 22, canvasWidth, 51);
                context.lineTo(canvasWidth, 65); context.bezierCurveTo(900, 34, 420, 10, 0, 75); context.fill();
                context.fillStyle = '#f17421';
                context.beginPath(); context.moveTo(0, 916); context.bezierCurveTo(480, 980, 1010, 925, canvasWidth, 868);
                context.lineTo(canvasWidth, canvasHeight); context.lineTo(0, canvasHeight); context.fill();
                context.strokeStyle = '#cf8c15'; context.lineWidth = 8; context.strokeRect(18, 68, canvasWidth - 36, canvasHeight - 136);

                context.save();
                context.globalAlpha = .5;
                context.drawImage(logo, 900, 125, 480, 480);
                context.restore();

                context.textAlign = 'left'; context.textBaseline = 'alphabetic';
                context.font = 'bold 43px Georgia'; context.fillStyle = '#151d24';
                const brandWidth = context.measureText('PT EQUITYWORLD FUTURES').width;
                const brandStartX = (canvasWidth - 118 - 18 - brandWidth) / 2;
                const brandTextX = brandStartX + 136;
                context.drawImage(logo, brandStartX, 78, 118, 100);
                context.fillText('PT EQUITYWORLD', brandTextX, 137);
                const companyWidth = context.measureText('PT EQUITYWORLD ').width;
                context.fillStyle = '#f17421'; context.fillText('FUTURES', brandTextX + companyWidth, 137);
                context.textAlign = 'center'; context.fillStyle = '#151d24'; context.font = '40px Georgia';
                context.fillText('SERTIFIKAT MAGANG', canvasWidth / 2, 205);

                context.font = '25px Arial'; context.fillStyle = '#151d24';
                context.fillText('Diberikan kepada:', 700, 300);
                context.strokeStyle = '#f17421'; context.lineWidth = 2;
                context.beginPath(); context.moveTo(535, 330); context.lineTo(865, 330); context.stroke();
                context.fillStyle = '#f17421'; context.save(); context.translate(700, 330); context.rotate(Math.PI / 4);
                context.fillRect(-7, -7, 14, 14); context.restore();

                let nameSize = 48;
                context.font = `bold ${nameSize}px Georgia`;
                while (context.measureText(details.full_name).width > 1050 && nameSize > 28) {
                    nameSize -= 2;
                    context.font = `bold ${nameSize}px Georgia`;
                }
                context.fillStyle = '#1b2731'; context.fillText(details.full_name, 700, 415);
                context.strokeStyle = '#ef9d79'; context.lineWidth = 2;
                context.beginPath(); context.moveTo(305, 455); context.lineTo(1095, 455); context.stroke();
                context.fillStyle = '#151d24'; context.font = '25px Arial';
                context.fillText('Telah mengikuti dan menyelesaikan Program Magang di', 700, 495);
                context.font = 'bold 30px Georgia'; context.fillText(details.company_name, 700, 542);

                const period = 'Periode Magang';
                context.strokeStyle = '#d6b77d'; context.lineWidth = 3;
                context.beginPath(); context.roundRect(430, 585, 540, 92, 14); context.stroke();
                context.fillStyle = '#ee741d'; context.beginPath(); context.roundRect(550, 567, 300, 40, 8); context.fill();
                context.fillStyle = '#fff'; context.font = 'bold 21px Arial'; context.fillText(period, 700, 594);
                context.fillStyle = '#151d24'; context.font = 'bold 21px Arial';
                context.fillText(formatDate(details.period_start) + '  -  ' + formatDate(details.period_end), 700, 650);

                context.fillStyle = '#151d24'; context.font = 'bold 22px Arial';
                context.fillText(details.signer_title, canvasWidth / 2, 710);
                context.font = 'bold 19px Arial'; context.fillText(details.company_name, canvasWidth / 2, 740);
                context.strokeStyle = '#68717a'; context.lineWidth = 1.5;
                context.drawImage(signature, canvasWidth / 2 - 40, 745, 80, 80);
                context.beginPath(); context.moveTo(590, 830); context.lineTo(810, 830); context.stroke();
                context.font = 'bold 19px Arial'; context.fillText(details.signer_name, canvasWidth / 2, 858);

                const sealX = 160;
                const sealY = 760;
                const ribbonGradient = context.createLinearGradient(sealX - 45, 0, sealX + 45, 0);
                ribbonGradient.addColorStop(0, '#9c1714');
                ribbonGradient.addColorStop(.5, '#d83325');
                ribbonGradient.addColorStop(1, '#9c1714');
                context.fillStyle = ribbonGradient;
                context.strokeStyle = '#a9271f';
                context.lineWidth = 1;
                context.beginPath();
                context.moveTo(sealX - 40, sealY + 45); context.lineTo(sealX - 13, sealY + 45);
                context.lineTo(sealX - 13, sealY + 125); context.lineTo(sealX - 27, sealY + 114);
                context.lineTo(sealX - 43, sealY + 125); context.closePath(); context.fill(); context.stroke();
                context.beginPath();
                context.moveTo(sealX + 13, sealY + 45); context.lineTo(sealX + 40, sealY + 45);
                context.lineTo(sealX + 43, sealY + 125); context.lineTo(sealX + 27, sealY + 114);
                context.lineTo(sealX + 13, sealY + 125); context.closePath(); context.fill(); context.stroke();

                const medalGradient = context.createRadialGradient(sealX - 24, sealY - 28, 4, sealX, sealY, 74);
                medalGradient.addColorStop(0, '#fff5ce');
                medalGradient.addColorStop(.72, '#e8c45b');
                medalGradient.addColorStop(1, '#b9871e');
                context.save();
                context.shadowColor = 'rgba(86, 62, 16, .22)';
                context.shadowBlur = 9;
                context.shadowOffsetY = 3;
                context.fillStyle = medalGradient;
                context.beginPath(); context.arc(sealX, sealY, 74, 0, Math.PI * 2); context.fill();
                context.restore();
                context.strokeStyle = '#fff0b0'; context.lineWidth = 2;
                context.beginPath(); context.arc(sealX, sealY, 68, 0, Math.PI * 2); context.stroke();

                const medalCenter = context.createRadialGradient(sealX - 19, sealY - 22, 3, sealX, sealY, 62);
                medalCenter.addColorStop(0, '#fffdf1');
                medalCenter.addColorStop(1, '#fff1c1');
                context.fillStyle = medalCenter;
                context.beginPath(); context.arc(sealX, sealY, 62, 0, Math.PI * 2); context.fill();
                context.strokeStyle = '#d4ad4b'; context.lineWidth = 1.5;
                context.beginPath(); context.arc(sealX, sealY, 57, 0, Math.PI * 2); context.stroke();

                context.save();
                context.translate(sealX, sealY - 31);
                context.beginPath();
                for (let point = 0; point < 10; point++) {
                    const angle = -Math.PI / 2 + point * Math.PI / 5;
                    const radius = point % 2 === 0 ? 8 : 3.5;
                    const x = Math.cos(angle) * radius;
                    const y = Math.sin(angle) * radius;
                    if (point === 0) context.moveTo(x, y);
                    else context.lineTo(x, y);
                }
                context.closePath();
                context.fillStyle = '#b78a25';
                context.fill();
                context.restore();

                context.fillStyle = '#8d691d'; context.font = 'bold 10px Arial';
                context.fillText('COMMITMENT', sealX, sealY - 6);
                context.fillText('INTEGRITY', sealX, sealY + 12);
                context.fillText('PROFESSIONAL', sealX, sealY + 30);
                context.textAlign = 'center'; context.fillStyle = '#68717a'; context.font = '13px Arial';
                context.fillText(details.certificate_number + '  |  Diterbitkan ' + formatDate(details.issued_at), canvasWidth / 2, 900);
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
                const png = await canvasToBlob(canvas, 'image/png', undefined, 'Sertifikat gagal disiapkan sebagai PNG.');
                const jpeg = await canvasToBlob(canvas, 'image/jpeg', .94, 'Sertifikat gagal disiapkan sebagai PDF.');
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
                object(3, '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 841.89 595.28] /Resources << /XObject << /Im0 4 0 R >> >> /Contents 5 0 R >>');
                offsets[4] = length;
                addText('4 0 obj\n<< /Type /XObject /Subtype /Image /Width ' + canvasWidth + ' /Height ' + canvasHeight + ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ' + jpeg.length + ' >>\nstream\n');
                add(jpeg);
                addText('\nendstream\nendobj\n');
                const content = 'q\n841.89 0 0 595.28 0 0 cm\n/Im0 Do\nQ';
                object(5, '<< /Length ' + encoder.encode(content).length + ' >>\nstream\n' + content + '\nendstream');
                const xrefOffset = length;
                addText('xref\n0 6\n0000000000 65535 f \n');
                for (let i = 1; i <= 5; i++) addText(String(offsets[i]).padStart(10, '0') + ' 00000 n \n');
                addText('trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n' + xrefOffset + '\n%%EOF');
                return new Blob(parts, {type: 'application/pdf'});
            }

            const downloadError = document.getElementById('certificateDownloadError');
            const downloadStatus = document.getElementById('certificateDownloadStatus');
            const downloadPngButton = document.getElementById('downloadPng');
            const downloadPdfButton = document.getElementById('downloadPdf');
            let downloadFiles = null;
            const reportDownloadError = error => {
                downloadError.textContent = error instanceof Error ? error.message : 'Sertifikat gagal dibuat.';
                downloadError.hidden = false;
                downloadStatus.hidden = true;
            };
            downloadPngButton.addEventListener('click', () => {
                if (downloadFiles) downloadBlob(downloadFiles.png, details.certificate_number + '.png');
            });
            downloadPdfButton.addEventListener('click', () => {
                if (downloadFiles) downloadBlob(downloadFiles.pdf, details.certificate_number + '.pdf');
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
