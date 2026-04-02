<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
  use Queueable;

  public function __construct(public string $token) {}

  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  public function toMail(object $notifiable): MailMessage
  {
    $url = url(route('password.reset', [
      'token' => $this->token,
      'email' => $notifiable->getEmailForPasswordReset(),
    ], false));

    return (new MailMessage)
      ->subject('【Campus Board】パスワード再設定のご案内')
      ->greeting($notifiable->nickname . ' さん')
      ->line('パスワード再設定のリクエストを受け付けました。')
      ->line('下のボタンをクリックして、新しいパスワードを設定してください。')
      ->action('パスワードを再設定する', $url)
      ->line('このリンクは ' . config('auth.passwords.users.expire', 60) . ' 分間有効です。')
      ->line('心当たりがない場合は、このメールを無視してください。パスワードは変更されません。')
      ->salutation('Campus Board');
  }
}
