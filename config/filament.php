<?php

return [
  /*php artisan config:casheを使用すると設定以外でenv関数を使用すると
  参照できないためconfigから受け渡す形に変更。 */
  'path' => env('FILAMENT_PATH', 'admin'),
];
