<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invitation</title>
</head>
<body>
    <h2>You're invited!</h2>

    <p>
        You have been invited to join
        <strong>{{ $invitation->company->name }}</strong>.
    </p>

    <p>
        Your role:
        <strong>{{ ucfirst($invitation->role) }}</strong>
    </p>

    <p>
        Click the button below to accept the invitation and create your account.
    </p>

    <p>
        <a href="{{ route('invitations.accept', $invitation->token) }}"
           style="display:inline-block;padding:10px 20px;background:#4f46e5;color:white;text-decoration:none;border-radius:5px;">
            Accept Invitation
        </a>
    </p>

    <p>This invitation will expire in 7 days.</p>
</body>
</html>