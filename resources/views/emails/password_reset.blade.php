<p>Hi {{ $name }},</p>

<p>You requested a password reset. Click the button below to reset your password:</p>

<p>
    <a href="{{ 'https://ancovaedu.vercel.app/reset-password/' . $token }}" style="display:inline-block; padding:10px 20px; background:#4CAF50; color:#ffffff; text-decoration:none; border-radius:5px;">
        Reset Password
    </a>
</p>

<p>Or use this link:</p>

<p>
    <a href="{{ 'https://ancovaedu.vercel.app/reset-password/' . $token }}">
        {{ 'https://ancovaedu.vercel.app/reset-password/' . $token }}
    </a>
</p>

<p>If you did not request this, please ignore this email.</p>

<p>Thanks,<br>Ancova Edu</p>
