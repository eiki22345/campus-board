<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportResolved extends Notification
{
  use Queueable;

  protected $target_content; // スネークケースで定義

  /**
   * Create a new notification instance.
   */
  public function __construct(string $target_content)
  {
    $this->target_content = $target_content;
  }

  /**
   * Get the notification's delivery channels.
   */
  public function via(object $notifiable): array
  {
    return ['database']; // データベースに保存
  }

  /**
   * Get the array representation of the notification.
   */
  public function toArray(object $notifiable): array
  {
    return [
      'message' => "ご報告いただいた投稿（{$this->target_content}...）を確認し、削除等の対応を行いました。ご協力ありがとうございました。",
      'link' => null,
      'type' => 'report_resolved',
    ];
  }
}
