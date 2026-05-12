<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Nuova richiesta di preventivo</title>
</head>
<body style="font-family: Arial, sans-serif; color: #192e42; line-height: 1.6;">
    <h2 style="color: #3d7bb8;">Nuova richiesta di preventivo</h2>

    <p>Hai ricevuto una nuova richiesta di preventivo dal sito Alves Drywall:</p>

    <table cellpadding="8" cellspacing="0" style="border-collapse: collapse; border: 1px solid #ddd;">
        <tr>
            <td style="background:#f4f7fb;"><strong>Nome</strong></td>
            <td>{{ $preventivo->name }}</td>
        </tr>
        <tr>
            <td style="background:#f4f7fb;"><strong>Telefono</strong></td>
            <td>{{ $preventivo->phone }}</td>
        </tr>
        <tr>
            <td style="background:#f4f7fb;"><strong>Email</strong></td>
            <td>{{ $preventivo->email ?: '—' }}</td>
        </tr>
        <tr>
            <td style="background:#f4f7fb; vertical-align: top;"><strong>Messaggio</strong></td>
            <td>{!! nl2br(e($preventivo->message)) !!}</td>
        </tr>
        <tr>
            <td style="background:#f4f7fb;"><strong>Ricevuto il</strong></td>
            <td>{{ $preventivo->created_at?->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <p style="margin-top: 24px; color: #6b7c91; font-size: 12px;">
        Email automatica inviata da Alves Drywall (ambiente di sviluppo / Mailhog).
    </p>
</body>
</html>
