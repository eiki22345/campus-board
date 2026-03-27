<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportResolved extends Notification
{
  use Queueable;

  /**
   * Get the notification's delivery channels.
   */
  public function via(object $notifiable): array
  {
    return ['database'];
  }

  /**
   * Get the array representation of the notification.
   */
  public function toArray(object $notifiable): array
  {
    return [
      'message' => 'ご報告いただいた投稿に対応しました。ご協力ありがとうございました。',
      'link' => null,
      'type' => 'report_resolved',
    ];
  }
}
