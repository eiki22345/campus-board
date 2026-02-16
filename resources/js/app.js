import './bootstrap';

import Alpine from 'alpinejs';
import DOMPurify from 'dompurify';

window.Alpine = Alpine;
window.DOMPurify = DOMPurify;

Alpine.start();

// 全てのいいねボタンを取得してイベントを設定
