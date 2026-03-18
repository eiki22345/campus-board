<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeletionRequested extends Notification
{
  use Queueable;

  public function __construct(private string $cancelUrl) {}

  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  public function toMail(object $notifiable): MailMessage
  {
    return (new MailMessage)
      ->subject('【Campus Board】アカウント削除のご確認')
      ->greeting($notifiable->nickname . ' さん')
      ->line('アカウントの削除が申請されました。')
      ->line('7日後（' . now()->addDays(7)->format('Y年m月d日') . '）に完全に削除されます。')
      ->line('心当たりがない場合、または削除を取り消す場合は下のボタンをクリックしてください。')
      ->action('削除をキャンセルする', $this->cancelUrl)
      ->line('このリンクは7日間有効です。')
      ->salutation('Campus Board');
  }
}
